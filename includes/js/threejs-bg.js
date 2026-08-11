/**
 * Jenny's Cosmetics | Three.js Particle Background
 * Golden floating particles responding to mouse
 */
(function () {
    'use strict';

    class ParticleBG {
        constructor(canvasId, options = {}) {
            this.canvas = document.getElementById(canvasId);
            if (!this.canvas) return;

            this.ctx = this.canvas.getContext('2d');
            this.opts = Object.assign({
                count: 120,
                color: '244, 180, 0',
                maxRadius: 3,
                speed: 0.5,
                connectDistance: 120,
                mouseRadius: 150
            }, options);

            this.particles = [];
            this.mouse = { x: -9999, y: -9999 };
            this.animId = null;

            this.resize();
            this.createParticles();
            this.bindEvents();
            this.animate();
        }

        resize() {
            this.canvas.width  = this.canvas.offsetWidth  || window.innerWidth;
            this.canvas.height = this.canvas.offsetHeight || window.innerHeight;
        }

        createParticles() {
            this.particles = [];
            for (let i = 0; i < this.opts.count; i++) {
                this.particles.push({
                    x:  Math.random() * this.canvas.width,
                    y:  Math.random() * this.canvas.height,
                    r:  Math.random() * this.opts.maxRadius + 0.5,
                    dx: (Math.random() - 0.5) * this.opts.speed,
                    dy: (Math.random() - 0.5) * this.opts.speed,
                    o:  Math.random() * 0.5 + 0.1,
                    pulse: Math.random() * Math.PI * 2
                });
            }
        }

        bindEvents() {
            window.addEventListener('resize', () => {
                this.resize();
                this.createParticles();
            });

            document.addEventListener('mousemove', e => {
                const rect = this.canvas.getBoundingClientRect();
                this.mouse.x = e.clientX - rect.left;
                this.mouse.y = e.clientY - rect.top;
            });

            document.addEventListener('mouseleave', () => {
                this.mouse.x = -9999;
                this.mouse.y = -9999;
            });
        }

        draw() {
            const ctx = this.ctx;
            const W   = this.canvas.width;
            const H   = this.canvas.height;
            const c   = this.opts.color;

            ctx.clearRect(0, 0, W, H);

            this.particles.forEach((p, i) => {
                // Pulse opacity
                p.pulse += 0.02;
                const alpha = p.o + Math.sin(p.pulse) * 0.1;

                // Mouse repulsion / attraction
                const dx = p.x - this.mouse.x;
                const dy = p.y - this.mouse.y;
                const dist = Math.sqrt(dx * dx + dy * dy);

                if (dist < this.opts.mouseRadius) {
                    const force = (this.opts.mouseRadius - dist) / this.opts.mouseRadius;
                    p.x += (dx / dist) * force * 2;
                    p.y += (dy / dist) * force * 2;
                }

                // Move
                p.x += p.dx;
                p.y += p.dy;

                // Bounce
                if (p.x < 0 || p.x > W) p.dx *= -1;
                if (p.y < 0 || p.y > H) p.dy *= -1;

                // Draw particle
                ctx.beginPath();
                ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                ctx.fillStyle = `rgba(${c}, ${Math.min(1, Math.max(0, alpha))})`;
                ctx.fill();

                // Connect nearby particles
                for (let j = i + 1; j < this.particles.length; j++) {
                    const p2   = this.particles[j];
                    const ddx  = p.x - p2.x;
                    const ddy  = p.y - p2.y;
                    const d    = Math.sqrt(ddx * ddx + ddy * ddy);

                    if (d < this.opts.connectDistance) {
                        const lineAlpha = (1 - d / this.opts.connectDistance) * 0.3;
                        ctx.beginPath();
                        ctx.moveTo(p.x, p.y);
                        ctx.lineTo(p2.x, p2.y);
                        ctx.strokeStyle = `rgba(${c}, ${lineAlpha})`;
                        ctx.lineWidth = 0.6;
                        ctx.stroke();
                    }
                }
            });
        }

        animate() {
            this.draw();
            this.animId = requestAnimationFrame(() => this.animate());
        }

        destroy() {
            if (this.animId) cancelAnimationFrame(this.animId);
        }
    }

    // Expose globally
    window.ParticleBG = ParticleBG;

    // Auto-init on hero canvas
    document.addEventListener('DOMContentLoaded', () => {
        if (document.getElementById('heroParticleCanvas')) {
            new ParticleBG('heroParticleCanvas', { count: 80, maxRadius: 2.5 });
        }
        if (document.getElementById('contactParticleCanvas')) {
            new ParticleBG('contactParticleCanvas', { count: 60 });
        }
    });
})();
