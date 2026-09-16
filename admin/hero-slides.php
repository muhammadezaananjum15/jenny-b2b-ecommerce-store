<?php
// admin/hero-slides.php | Hero Carousel Manager v2.0
require_once 'includes/auth_check.php';
require_once '../config/db.php';

$msg = ''; $msgType = '';

// Handle Slide Add/Edit/Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'save_slide') {
        $id = (int)($_POST['slide_id'] ?? 0);
        $badge_text = trim($_POST['badge_text'] ?? '');
        $subtitle = trim($_POST['subtitle'] ?? '');
        $title = trim($_POST['title'] ?? '');
        $title_highlight = trim($_POST['title_highlight'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $btn1_text = trim($_POST['btn1_text'] ?? '');
        $btn1_link = trim($_POST['btn1_link'] ?? '');
        $btn2_text = trim($_POST['btn2_text'] ?? '');
        $btn2_link = trim($_POST['btn2_link'] ?? '');
        $sort_order = (int)($_POST['sort_order'] ?? 1);
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        $image = trim($_POST['existing_image'] ?? '');

        if (!empty($_FILES['image']['name'])) {
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg','jpeg','png','webp'])) {
                $newName = 'hero_slide_' . time() . '.' . $ext;
                $uploadDir = '../img/';
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $newName)) {
                    $image = $newName;
                }
            }
        }

        try {
            if ($id > 0) {
                $stmt = $pdo->prepare("UPDATE hero_slides SET badge_text=?, subtitle=?, title=?, title_highlight=?, description=?, btn1_text=?, btn1_link=?, btn2_text=?, btn2_link=?, sort_order=?, is_active=?, image=? WHERE id=?");
                $stmt->execute([$badge_text, $subtitle, $title, $title_highlight, $description, $btn1_text, $btn1_link, $btn2_text, $btn2_link, $sort_order, $is_active, $image, $id]);
                $msg = 'Hero slide updated successfully!'; $msgType = 'success';
            } else {
                $stmt = $pdo->prepare("INSERT INTO hero_slides (badge_text, subtitle, title, title_highlight, description, btn1_text, btn1_link, btn2_text, btn2_link, sort_order, is_active, image) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
                $stmt->execute([$badge_text, $subtitle, $title, $title_highlight, $description, $btn1_text, $btn1_link, $btn2_text, $btn2_link, $sort_order, $is_active, $image]);
                $msg = 'New hero slide added!'; $msgType = 'success';
            }
        } catch (PDOException $e) {
            $msg = 'Error: ' . $e->getMessage(); $msgType = 'error';
        }
    } elseif ($action === 'delete_slide') {
        $id = (int)($_POST['slide_id'] ?? 0);
        $pdo->prepare("DELETE FROM hero_slides WHERE id=?")->execute([$id]);
        $msg = 'Slide deleted.'; $msgType = 'success';
    }
}

$slides = $pdo->query("SELECT * FROM hero_slides ORDER BY sort_order ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<link rel="icon" type="image/png" sizes="32x32" href="../img/favicon-32x32.png">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Hero Slides | Jenny's Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="css/admin.css">
</head>
<body>
<?php include 'includes/sidebar.php'; ?>

<div class="admin-main">
    <div class="admin-topbar">
        <div class="topbar-title">Hero Carousel Management</div>
        <div class="topbar-right">
            <button class="btn-gold" onclick="openAddSlideModal()"><i class="fas fa-plus"></i> Add New Slide</button>
        </div>
    </div>

    <div class="admin-content">
        <?php if ($msg): ?>
        <div class="toast <?= $msgType ?>" style="position:relative;margin-bottom:16px;animation:none;">
            <i class="fas fa-<?= $msgType==='success'?'check-circle':'exclamation-circle' ?>"></i> <?= htmlspecialchars($msg) ?>
        </div>
        <?php endif; ?>

        <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(320px, 1fr));gap:20px;">
            <?php foreach ($slides as $s): ?>
            <div class="table-card" style="margin-bottom:0;display:flex;flex-direction:column;justify-content:space-between;">
                <div>
                    <div class="slide-preview">
                        <img src="../img/<?= htmlspecialchars($s['image'] ?: 'hero-img.png') ?>" onerror="this.src='../img/hero-img.png'">
                        <div class="slide-preview-overlay">
                            <div class="slide-preview-badge"><?= htmlspecialchars($s['badge_text']) ?></div>
                            <div class="slide-preview-title"><?= htmlspecialchars($s['subtitle']) ?> <span style="color:var(--gold);"><?= htmlspecialchars($s['title']) ?> <?= htmlspecialchars($s['title_highlight']) ?></span></div>
                        </div>
                    </div>
                    <div style="padding:16px;">
                        <p style="font-size:0.8rem;color:#aaa;margin-bottom:12px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;"><?= htmlspecialchars($s['description']) ?></p>
                        <div style="display:flex;justify-content:space-between;align-items:center;font-size:0.75rem;color:#888;">
                            <span>Sort Order: <strong style="color:var(--gold);"><?= $s['sort_order'] ?></strong></span>
                            <span>Status: <span class="badge-status badge-<?= $s['is_active'] ? 'active' : 'inactive' ?>"><?= $s['is_active'] ? 'Active' : 'Hidden' ?></span></span>
                        </div>
                    </div>
                </div>
                <div style="padding:12px 16px;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:8px;">
                    <button class="btn-action btn-edit" onclick="editSlide(<?= htmlspecialchars(json_encode($s)) ?>)" title="Edit Slide"><i class="fas fa-edit"></i> Edit</button>
                    <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this slide?')">
                        <input type="hidden" name="action" value="delete_slide">
                        <input type="hidden" name="slide_id" value="<?= $s['id'] ?>">
                        <button type="submit" class="btn-action btn-delete" title="Delete"><i class="fas fa-trash"></i> Delete</button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- SLIDE MODAL -->
<div class="modal-overlay" id="slideModal">
    <div class="modal-box modal-lg">
        <div class="modal-header">
            <div class="modal-title"><i class="fas fa-images" style="color:var(--gold);margin-right:8px;"></i><span id="modalTitleText">Add Hero Slide</span></div>
            <button class="modal-close" onclick="closeModal('slideModal')"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="save_slide">
            <input type="hidden" name="slide_id" id="slideId" value="0">
            <input type="hidden" name="existing_image" id="slideExistingImg" value="">
            <div class="modal-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Badge Text</label>
                        <input type="text" name="badge_text" id="slideBadge" class="form-control" placeholder="✨ Premium Collection">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Subtitle</label>
                        <input type="text" name="subtitle" id="slideSubtitle" class="form-control" placeholder="Elevate Your">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Main Title *</label>
                        <input type="text" name="title" id="slideTitle" class="form-control" placeholder="Beauty &" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Title Highlight (Gold Color)</label>
                        <input type="text" name="title_highlight" id="slideHighlight" class="form-control" placeholder="Shine">
                    </div>
                    <div class="form-group form-full">
                        <label class="form-label">Description</label>
                        <textarea name="description" id="slideDesc" class="form-control" rows="2" placeholder="Slide caption description..."></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Button 1 Label</label>
                        <input type="text" name="btn1_text" id="slideBtn1Text" class="form-control" value="Shop Now">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Button 1 URL</label>
                        <input type="text" name="btn1_link" id="slideBtn1Link" class="form-control" value="products.php">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Button 2 Label</label>
                        <input type="text" name="btn2_text" id="slideBtn2Text" class="form-control" value="Explore">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Button 2 URL</label>
                        <input type="text" name="btn2_link" id="slideBtn2Link" class="form-control" value="cosmetics.php">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" id="slideSort" class="form-control" value="1">
                    </div>
                    <div class="form-group" style="justify-content:center;">
                        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;color:#aaa;font-size:0.875rem;">
                            <input type="checkbox" name="is_active" id="slideActive" value="1" checked> Active & Visible
                        </label>
                    </div>
                    <div class="form-group form-full">
                        <label class="form-label">Background Image</label>
                        <div class="upload-zone" onclick="document.getElementById('slideImgInput').click()">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Click to upload high-res image <span>browse</span></p>
                        </div>
                        <input type="file" id="slideImgInput" name="image" accept="image/*" style="display:none;" onchange="previewSlideImg(this)">
                        <img id="slideImgPreview" class="img-preview">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-outline-gold" onclick="closeModal('slideModal')">Cancel</button>
                <button type="submit" class="btn-gold"><i class="fas fa-save"></i> Save Slide</button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddSlideModal() {
    document.getElementById('modalTitleText').textContent = 'Add Hero Slide';
    document.getElementById('slideId').value = 0;
    document.getElementById('slideBadge').value = '';
    document.getElementById('slideSubtitle').value = '';
    document.getElementById('slideTitle').value = '';
    document.getElementById('slideHighlight').value = '';
    document.getElementById('slideDesc').value = '';
    document.getElementById('slideExistingImg').value = '';
    document.getElementById('slideImgPreview').style.display = 'none';
    openModal('slideModal');
}
function editSlide(s) {
    document.getElementById('modalTitleText').textContent = 'Edit Hero Slide';
    document.getElementById('slideId').value = s.id;
    document.getElementById('slideBadge').value = s.badge_text || '';
    document.getElementById('slideSubtitle').value = s.subtitle || '';
    document.getElementById('slideTitle').value = s.title || '';
    document.getElementById('slideHighlight').value = s.title_highlight || '';
    document.getElementById('slideDesc').value = s.description || '';
    document.getElementById('slideBtn1Text').value = s.btn1_text || '';
    document.getElementById('slideBtn1Link').value = s.btn1_link || '';
    document.getElementById('slideBtn2Text').value = s.btn2_text || '';
    document.getElementById('slideBtn2Link').value = s.btn2_link || '';
    document.getElementById('slideSort').value = s.sort_order;
    document.getElementById('slideActive').checked = s.is_active == 1;
    document.getElementById('slideExistingImg').value = s.image || '';
    const prev = document.getElementById('slideImgPreview');
    if (s.image) { prev.src = '../img/' + s.image; prev.style.display = 'block'; }
    else { prev.style.display = 'none'; }
    openModal('slideModal');
}
function previewSlideImg(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.getElementById('slideImgPreview');
            img.src = e.target.result; img.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
</body>
</html>
