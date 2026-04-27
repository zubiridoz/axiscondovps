/* CondomiNet Landing — Scroll Reveal & Navbar */
document.addEventListener('DOMContentLoaded', () => {
    // ── Navbar scroll effect ──
    const nav = document.querySelector('.ln-nav');
    window.addEventListener('scroll', () => {
        nav.classList.toggle('scrolled', window.scrollY > 60);
    });

    // ── Mobile toggle ──
    const toggle = document.querySelector('.ln-nav-toggle');
    const links = document.querySelector('.ln-nav-links');
    if (toggle) {
        toggle.addEventListener('click', () => links.classList.toggle('open'));
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.ln-nav')) links.classList.remove('open');
        });
    }

    // ── Scroll Reveal ──
    const reveals = document.querySelectorAll('.reveal');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, i) => {
            if (entry.isIntersecting) {
                setTimeout(() => entry.target.classList.add('visible'), i * 80);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12 });
    reveals.forEach(el => observer.observe(el));

    // ── Smooth scroll for anchor links ──
    document.querySelectorAll('a[href^="#"]').forEach(a => {
        a.addEventListener('click', (e) => {
            e.preventDefault();
            const target = document.querySelector(a.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                links.classList.remove('open');
            }
        });
    });

    // ── Animate counters in hero stats ──
    const counters = document.querySelectorAll('.ln-hero-stat strong');
    counters.forEach(el => {
        const target = parseInt(el.dataset.count || el.textContent.replace(/[^\d]/g, ''));
        const suffix = el.textContent.replace(/[\d,]+/, '');
        let current = 0;
        const step = Math.ceil(target / 50);
        const timer = setInterval(() => {
            current += step;
            if (current >= target) { current = target; clearInterval(timer); }
            el.textContent = current.toLocaleString() + suffix;
        }, 30);
    });
});
