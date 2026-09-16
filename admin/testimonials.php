<?php
// admin/testimonials.php | Full Testimonial CRUD & Control v2.0
require_once 'includes/auth_check.php';
require_once '../config/db.php';

$msg = ''; $msgType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'save_testimonial') {
        $id = (int)($_POST['testimonial_id'] ?? 0);
        $client_name = trim($_POST['client_name'] ?? '');
        $client_title = trim($_POST['client_title'] ?? 'Verified Customer');
        $review = trim($_POST['review'] ?? '');
        $rating = (int)($_POST['rating'] ?? 5);
        $sort_order = (int)($_POST['sort_order'] ?? 1);
        $is_visible = isset($_POST['is_visible']) ? 1 : 0;
        $client_img = trim($_POST['existing_img'] ?? '');

        if (!empty($_FILES['client_img']['name'])) {
            $ext = strtolower(pathinfo($_FILES['client_img']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg','jpeg','png','webp'])) {
                $newName = 'testimonial_' . time() . '.' . $ext;
                if (move_uploaded_file($_FILES['client_img']['tmp_name'], '../img/' . $newName)) {
                    $client_img = $newName;
                }
            }
        }

        try {
            if ($id > 0) {
                $stmt = $pdo->prepare("UPDATE testimonials SET client_name=?, client_title=?, review=?, rating=?, sort_order=?, is_visible=?, client_img=? WHERE id=?");
                $stmt->execute([$client_name, $client_title, $review, $rating, $sort_order, $is_visible, $client_img, $id]);
                $msg = 'Testimonial updated!'; $msgType = 'success';
            } else {
                $stmt = $pdo->prepare("INSERT INTO testimonials (client_name, client_title, review, rating, sort_order, is_visible, client_img) VALUES (?,?,?,?,?,?,?)");
                $stmt->execute([$client_name, $client_title, $review, $rating, $sort_order, $is_visible, $client_img]);
                $msg = 'Testimonial added!'; $msgType = 'success';
            }
        } catch (PDOException $e) {
            $msg = 'Error: ' . $e->getMessage(); $msgType = 'error';
        }
    } elseif ($action === 'toggle_visibility') {
        $id = (int)$_POST['testimonial_id'];
        $vis = (int)$_POST['is_visible'];
        $pdo->prepare("UPDATE testimonials SET is_visible=? WHERE id=?")->execute([$vis, $id]);
        $msg = 'Visibility updated!'; $msgType = 'success';
    } elseif ($action === 'delete_testimonial') {
        $id = (int)$_POST['testimonial_id'];
        $pdo->prepare("DELETE FROM testimonials WHERE id=?")->execute([$id]);
        $msg = 'Testimonial deleted.'; $msgType = 'success';
    }
}

$testimonials = $pdo->query("SELECT * FROM testimonials ORDER BY sort_order ASC, created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<link rel="icon" type="image/png" sizes="32x32" href="../img/favicon-32x32.png">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Testimonials | Jenny's Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="css/admin.css">
</head>
<body>
<?php include 'includes/sidebar.php'; ?>

<div class="admin-main">
    <div class="admin-topbar">
        <div class="topbar-title">Testimonials Management</div>
        <div class="topbar-right">
            <button class="btn-gold" onclick="openAddModal()"><i class="fas fa-plus"></i> Add Testimonial</button>
        </div>
    </div>

    <div class="admin-content">
        <?php if ($msg): ?>
        <div class="toast <?= $msgType ?>" style="position:relative;margin-bottom:16px;animation:none;">
            <i class="fas fa-<?= $msgType==='success'?'check-circle':'exclamation-circle' ?>"></i> <?= htmlspecialchars($msg) ?>
        </div>
        <?php endif; ?>

        <div class="table-card">
            <div class="table-header">
                <div class="table-title">Client Reviews & Testimonials</div>
            </div>
            <table class="admin-table">
                <thead><tr>
                    <th>Client</th>
                    <th>Title / Role</th>
                    <th>Review Content</th>
                    <th>Rating</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr></thead>
                <tbody>
                    <?php foreach ($testimonials as $t): ?>
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <img src="../img/<?= htmlspecialchars($t['client_img'] ?: 'testimonial-img 1.jpg') ?>" onerror="this.src='../img/testimonial-img 1.jpg'" class="user-avatar-md">
                                <div style="font-weight:600;color:#fff;"><?= htmlspecialchars($t['client_name']) ?></div>
                            </div>
                        </td>
                        <td style="color:#aaa;font-size:0.82rem;"><?= htmlspecialchars($t['client_title']) ?></td>
                        <td style="max-width:300px;font-size:0.8rem;color:#ccc;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;"><?= htmlspecialchars($t['review']) ?></td>
                        <td><span style="color:var(--gold);">★ <?= $t['rating'] ?></span></td>
                        <td><?= $t['sort_order'] ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="toggle_visibility">
                                <input type="hidden" name="testimonial_id" value="<?= $t['id'] ?>">
                                <input type="hidden" name="is_visible" value="<?= $t['is_visible'] ? 0 : 1 ?>">
                                <button type="submit" class="badge-status badge-<?= $t['is_visible'] ? 'active' : 'inactive' ?>" style="border:none;cursor:pointer;">
                                    <?= $t['is_visible'] ? 'Visible' : 'Hidden' ?>
                                </button>
                            </form>
                        </td>
                        <td>
                            <div class="action-btns">
                                <button class="btn-action btn-edit" onclick="editTestimonial(<?= htmlspecialchars(json_encode($t)) ?>)"><i class="fas fa-edit"></i></button>
                                <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this testimonial?')">
                                    <input type="hidden" name="action" value="delete_testimonial">
                                    <input type="hidden" name="testimonial_id" value="<?= $t['id'] ?>">
                                    <button type="submit" class="btn-action btn-delete"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($testimonials)): ?>
                    <tr><td colspan="7" class="empty-state"><i class="fas fa-star"></i>No testimonials found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL -->
<div class="modal-overlay" id="testModal">
    <div class="modal-box modal-lg">
        <div class="modal-header">
            <div class="modal-title"><i class="fas fa-star" style="color:var(--gold);margin-right:8px;"></i><span id="tModalTitle">Add Testimonial</span></div>
            <button class="modal-close" onclick="closeModal('testModal')"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="save_testimonial">
            <input type="hidden" name="testimonial_id" id="tId" value="0">
            <input type="hidden" name="existing_img" id="tExistingImg" value="">
            <div class="modal-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Client Name *</label>
                        <input type="text" name="client_name" id="tName" class="form-control" required placeholder="Lina Farooq">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Client Title / Role</label>
                        <input type="text" name="client_title" id="tTitle" class="form-control" placeholder="Beauty Blogger / Verified Customer">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Rating (1 to 5)</label>
                        <select name="rating" id="tRating" class="form-control">
                            <option value="5">★★★★★ 5 Stars</option>
                            <option value="4">★★★★☆ 4 Stars</option>
                            <option value="3">★★★☆☆ 3 Stars</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Display Order</label>
                        <input type="number" name="sort_order" id="tSort" class="form-control" value="1">
                    </div>
                    <div class="form-group form-full">
                        <label class="form-label">Review Text *</label>
                        <textarea name="review" id="tReview" class="form-control" rows="3" required placeholder="Customer feedback content..."></textarea>
                    </div>
                    <div class="form-group form-full">
                        <label class="form-label">Client Photo</label>
                        <div class="upload-zone" onclick="document.getElementById('tImgInput').click()">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Upload avatar image or <span>browse</span></p>
                        </div>
                        <input type="file" id="tImgInput" name="client_img" accept="image/*" style="display:none;" onchange="previewTImg(this)">
                        <img id="tImgPreview" class="img-preview">
                    </div>
                    <div class="form-group">
                        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;color:#aaa;font-size:0.875rem;">
                            <input type="checkbox" name="is_visible" id="tVisible" value="1" checked> Visible on website
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-outline-gold" onclick="closeModal('testModal')">Cancel</button>
                <button type="submit" class="btn-gold"><i class="fas fa-save"></i> Save Testimonial</button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddModal() {
    document.getElementById('tModalTitle').textContent = 'Add Testimonial';
    document.getElementById('tId').value = 0;
    document.getElementById('tName').value = '';
    document.getElementById('tTitle').value = 'Verified Customer';
    document.getElementById('tReview').value = '';
    document.getElementById('tExistingImg').value = '';
    document.getElementById('tImgPreview').style.display = 'none';
    openModal('testModal');
}
function editTestimonial(t) {
    document.getElementById('tModalTitle').textContent = 'Edit Testimonial';
    document.getElementById('tId').value = t.id;
    document.getElementById('tName').value = t.client_name;
    document.getElementById('tTitle').value = t.client_title || '';
    document.getElementById('tReview').value = t.review;
    document.getElementById('tRating').value = t.rating;
    document.getElementById('tSort').value = t.sort_order;
    document.getElementById('tVisible').checked = t.is_visible == 1;
    document.getElementById('tExistingImg').value = t.client_img || '';
    const prev = document.getElementById('tImgPreview');
    if (t.client_img) { prev.src = '../img/' + t.client_img; prev.style.display = 'block'; }
    else { prev.style.display = 'none'; }
    openModal('testModal');
}
function previewTImg(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.getElementById('tImgPreview');
            img.src = e.target.result; img.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
</body>
</html>
