<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<?php
$rawAnnouncements = is_array($announcements ?? null) ? $announcements : [];
$categoryMeta = [
    'all' => ['label' => 'Todos', 'icon' => 'bi-grid'],
    'general' => ['label' => 'General', 'icon' => 'bi-megaphone'],
    'maintenance' => ['label' => 'Mantenimiento', 'icon' => 'bi-wrench'],
    'urgent' => ['label' => 'Urgente', 'icon' => 'bi-exclamation-triangle'],
    'event' => ['label' => 'Evento', 'icon' => 'bi-calendar-event'],
];
$counts = ['all' => 0, 'general' => 0, 'maintenance' => 0, 'urgent' => 0, 'event' => 0];
foreach ($rawAnnouncements as $r) {
    $counts['all']++;
    $c = $r['category'] ?? 'general';
    if (isset($counts[$c]))
        $counts[$c]++;
}
$toRel = function (int $ts): string {
    $d = max(0, time() - $ts);
    if ($d < 60)
        return 'hace unos segundos';
    if ($d < 3600)
        return 'hace ' . intdiv($d, 60) . ' min';
    if ($d < 86400)
        return 'hace ' . intdiv($d, 3600) . ' h';
    return 'hace ' . intdiv($d, 86400) . ' d';
};
$jsonData = htmlspecialchars(json_encode($rawAnnouncements, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), ENT_QUOTES, 'UTF-8');
?>
<div id="an-data" style="display:none" data-items="<?= $jsonData ?>"></div>

<style>
    /* ── Hero ── */
    .cc-hero {
        background: #ffffff;
        border-radius: .5rem;
        padding: 0.85rem 1.25rem;
        border: 1px solid #e2e8f0;
        margin-bottom: 1.25rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .cc-hero-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .cc-hero-title {
        margin: 0;
        font-weight: 500;
        font-size: 1.05rem;
        color: #475569;
    }

    .cc-hero-divider {
        width: 1px;
        height: 22px;
        background-color: #cbd5e1;
    }

    .cc-hero-breadcrumb {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        font-size: 0.85rem;
        color: #64748b;
    }

    .cc-hero-breadcrumb i.bi-house-door {
        color: #3b82f6;
        font-size: 0.95rem;
    }

    .cc-hero-breadcrumb i.bi-chevron-right {
        font-size: 0.65rem;
        color: #94a3b8;
    }

    .cc-hero-btn {
        background: #238b71ff;
        color: #ffffff;
        border: none;
        border-radius: 0.45rem;
        padding: 0.65rem 1.4rem;
        font-size: 0.98rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.55rem;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        transition: all 0.25s ease;
    }

    .cc-hero-btn:hover {
        background: #5cad99ff;
        box-shadow: 0 6px 14px rgba(0, 0, 0, 0.15);
        transform: translateY(-1px);
    }


    /* ── Layout ── */
    .an-layout {
        display: grid;
        grid-template-columns: 250px 1fr;
        gap: 1rem
    }

    .an-sidebar {
        border: 1px solid #d9e1eb;
        border-radius: .6rem;
        background: #fff;
        padding: 1rem .85rem;
        height: fit-content
    }

    .an-sidebar h3 {
        font-size: 1rem;
        margin-bottom: .8rem;
        color: #0f172a;
        font-weight: 700
    }

    .an-sidebar .label {
        color: #334155;
        font-size: .88rem;
        font-weight: 600;
        margin-bottom: .4rem
    }

    .an-search-wrap {
        position: relative;
        margin-bottom: .85rem
    }

    .an-search-wrap i {
        position: absolute;
        left: .6rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8
    }

    .an-search {
        width: 100%;
        border: 1px solid #d0d8e2;
        border-radius: .42rem;
        background: #fff;
        color: #334155;
        font-size: .86rem;
        padding: .48rem .7rem .48rem 1.9rem
    }

    .an-search:focus {
        outline: none;
        border-color: #93a5bc;
        box-shadow: 0 0 0 3px rgba(147, 165, 188, .12)
    }

    .an-cat-list {
        border-bottom: 1px solid #e2e8f0;
        padding-bottom: .6rem;
        margin-bottom: .85rem
    }

    .an-cat-btn {
        width: 100%;
        border: none;
        background: transparent;
        border-radius: .42rem;
        padding: .48rem .55rem;
        color: #1e293b;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: .9rem;
        cursor: pointer
    }

    .an-cat-btn .left {
        display: inline-flex;
        align-items: center;
        gap: .55rem
    }

    .an-cat-btn .count {
        color: #64748b;
        font-size: .8rem
    }

    .an-cat-btn.active {
        background: #4b5f78;
        color: #fff;
        font-weight: 600
    }

    .an-cat-btn:hover {
        background: #f8fafc
    }

    .an-cat-btn.active:hover {
        background: #41556f
    }

    .an-order {
        width: 100%;
        border: 1px solid #d0d8e2;
        border-radius: .42rem;
        font-size: .86rem;
        color: #334155;
        padding: .48rem .6rem;
        background: #fff
    }

    /* ── Feed ── */
    .an-main {
        min-width: 0
    }

    .an-feed {
        display: grid;
        gap: .7rem
    }

    .an-card {
        border: 1px solid #d9e1eb;
        border-radius: .5rem;
        background: #fff;
        cursor: pointer;
        transition: box-shadow .2s, border-color .2s;
        position: relative
    }

    .an-card:hover {
        border-color: #b7c8dc;
        box-shadow: 0 3px 10px rgba(15, 23, 42, .07)
    }

    .an-card-cover {
        width: 100%;
        height: 140px;
        object-fit: cover;
        display: block;
        background: #e2e8f0;
        border-radius: .5rem .5rem 0 0
    }

    .an-card-head {
        display: flex;
        align-items: center;
        gap: .55rem;
        padding: .55rem .75rem .35rem
    }

    .an-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #e2e8f0;
        color: #334155;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: .72rem;
        flex-shrink: 0
    }

    .an-author {
        font-size: .86rem;
        font-weight: 650;
        color: #0f172a
    }

    .an-meta {
        color: #57708f;
        font-size: .72rem
    }

    .an-badge {
        margin-left: auto;
        border: 1px solid #d0d8e2;
        border-radius: 999px;
        color: #334155;
        background: #f8fafc;
        font-size: .66rem;
        font-weight: 600;
        padding: .14rem .4rem
    }

    .an-card-kebab {
        position: relative;
        margin-left: .3rem;
        flex-shrink: 0
    }

    .an-card-body {
        padding: .05rem .75rem .45rem
    }

    .an-snippet {
        color: #334155;
        font-size: .82rem;
        line-height: 1.42;
        margin: 0;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden
    }

    .an-footer {
        padding: .4rem .75rem;
        display: flex;
        gap: 1rem;
        color: #475569;
        font-size: .78rem;
        border-top: 1px solid #edf2f7
    }

    .an-foot-item {
        display: inline-flex;
        align-items: center;
        gap: .28rem
    }

    .an-empty {
        border: 1px dashed #d4deea;
        border-radius: .6rem;
        background: #fbfdff;
        min-height: 380px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: #57708f
    }

    .an-empty i {
        display: block;
        font-size: 2rem;
        margin-bottom: .6rem;
        color: #8ea2b9
    }

    /* ── Modal Overlay ── */
    .an-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, .45);
        z-index: 9000;
        display: none;
        align-items: center;
        justify-content: center;
        animation: anFadeIn .2s
    }

    .an-modal-overlay.show {
        display: flex
    }

    @keyframes anFadeIn {
        from {
            opacity: 0
        }

        to {
            opacity: 1
        }
    }

    .an-modal {
        background: #fff;
        border-radius: .65rem;
        box-shadow: 0 20px 60px rgba(15, 23, 42, .22);
        width: 520px;
        max-width: 95vw;
        max-height: 90vh;
        overflow-y: auto;
        position: relative;
        animation: anSlideUp .25s ease
    }

    .an-modal.expanded {
        width: 820px
    }

    @keyframes anSlideUp {
        from {
            transform: translateY(20px);
            opacity: 0
        }

        to {
            transform: translateY(0);
            opacity: 1
        }
    }

    .an-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 1.2rem 1.3rem .45rem
    }

    .an-modal-header h3 {
        margin: 0;
        font-size: 1.05rem;
        font-weight: 700;
        color: #0f172a
    }

    .an-modal-header p {
        margin: .1rem 0 0;
        font-size: .8rem;
        color: #64748b
    }

    .an-modal-close {
        background: none;
        border: none;
        font-size: 1.2rem;
        color: #94a3b8;
        cursor: pointer;
        padding: .25rem
    }

    .an-modal-close:hover {
        color: #0f172a
    }

    .an-modal-body {
        padding: 0 1.3rem 1rem;
        display: flex;
        gap: 1.4rem
    }

    .an-modal-left {
        flex: 1;
        min-width: 0
    }

    .an-modal-right {
        width: 260px;
        flex-shrink: 0;
        display: none;
        border-left: 1px solid #e2e8f0;
        padding-left: 1.2rem
    }

    .an-modal.expanded .an-modal-right {
        display: block
    }

    .an-modal-footer {
        padding: .7rem 1.3rem 1.1rem;
        display: flex;
        justify-content: flex-end;
        gap: .5rem;
        border-top: 1px solid #edf2f7
    }

    .cc-btn {
        border: 1px solid #d0d8e2;
        background: #fff;
        color: #0f172a;
        border-radius: .42rem;
        font-size: .9rem;
        line-height: 1;
        padding: .52rem .7rem;
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        text-decoration: none;
        cursor: pointer
    }

    .cc-btn:hover {
        background: #f8fafc;
        border-color: #c3cfde;
        color: #0f172a
    }

    .cc-btn.primary {
        border-color: #4b5f78;
        background: #4b5f78;
        color: #fff;
        font-weight: 600;
        padding-inline: .85rem
    }

    .cc-btn.primary:hover {
        background: #41556f;
        border-color: #41556f
    }

    .an-gear-btn {
        background: none;
        border: none;
        color: #94a3b8;
        font-size: 1rem;
        cursor: pointer;
        padding: .25rem;
        margin-left: .5rem;
        transition: color .2s
    }

    .an-gear-btn:hover,
    .an-gear-btn.active {
        color: #4b5f78
    }

    /* ── Category Chips ── */
    .an-cat-chips {
        display: flex;
        flex-wrap: wrap;
        gap: .35rem;
        margin-bottom: .7rem
    }

    .an-cat-chip {
        border: 1px solid #d0d8e2;
        background: #fff;
        border-radius: .38rem;
        padding: .32rem .6rem;
        font-size: .82rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        color: #334155;
        transition: all .15s
    }

    .an-cat-chip.active {
        background: #4b5f78;
        color: #fff;
        border-color: #4b5f78;
        font-weight: 600
    }

    .an-cat-chip:hover {
        border-color: #b7c8dc
    }

    /* ── Rich Text Editor ── */
    .an-editor-wrap {
        margin-bottom: .7rem
    }

    .an-editor-toolbar {
        display: flex;
        gap: .15rem;
        padding: .3rem .4rem;
        border: 1px solid #d0d8e2;
        border-radius: .38rem .38rem 0 0;
        background: #f8fafc
    }

    .an-editor-toolbar button {
        background: none;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: .3rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #475569;
        font-size: .88rem;
        cursor: pointer
    }

    .an-editor-toolbar button:hover {
        background: #e2e8f0
    }

    .an-editor-content {
        border: 1px solid #d0d8e2;
        border-top: none;
        border-radius: 0 0 .38rem .38rem;
        min-height: 140px;
        padding: .65rem .7rem;
        font-size: .88rem;
        color: #0f172a;
        line-height: 1.5;
        outline: none;
        font-family: inherit
    }

    .an-editor-content:empty::before {
        content: attr(data-placeholder);
        color: #94a3b8;
        pointer-events: none
    }

    /* ── File Chips ── */
    .an-file-section {
        margin-bottom: .6rem
    }

    .an-file-btns {
        display: flex;
        gap: .35rem;
        margin-bottom: .45rem
    }

    .an-file-btn {
        border: 1px solid #d0d8e2;
        background: #fff;
        border-radius: .38rem;
        padding: .35rem .6rem;
        font-size: .82rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        color: #334155
    }

    .an-file-btn:hover {
        border-color: #b7c8dc;
        background: #f8fafc
    }

    .an-file-chips {
        display: flex;
        flex-wrap: wrap;
        gap: .3rem
    }

    .an-file-chip {
        background: #f1f5f9;
        border-radius: .3rem;
        padding: .2rem .45rem;
        font-size: .76rem;
        display: inline-flex;
        align-items: center;
        gap: .25rem;
        color: #334155;
        border: 1px solid #e2e8f0
    }

    .an-file-chip button {
        background: none;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        font-size: .8rem;
        padding: 0;
        line-height: 1
    }

    .an-file-chip button:hover {
        color: #ef4444
    }

    .an-file-limit {
        font-size: .72rem;
        color: #94a3b8;
        margin-top: .2rem
    }

    /* ── Toggle Switch ── */
    .an-toggle {
        display: flex;
        align-items: center;
        gap: .55rem;
        padding: .45rem 0;
        font-size: .86rem;
        color: #334155;
        cursor: pointer
    }

    .an-toggle-track {
        width: 38px;
        height: 20px;
        border-radius: 10px;
        background: #cbd5e1;
        position: relative;
        transition: background .2s;
        flex-shrink: 0
    }

    .an-toggle-track::after {
        content: '';
        position: absolute;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #fff;
        top: 2px;
        left: 2px;
        transition: transform .2s;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .15)
    }

    .an-toggle input {
        display: none
    }

    .an-toggle input:checked+.an-toggle-track {
        background: #4b5f78
    }

    .an-toggle input:checked+.an-toggle-track::after {
        transform: translateX(18px)
    }

    .an-radio-group {
        display: flex;
        flex-direction: column;
        gap: .35rem;
        margin-top: .4rem
    }

    .an-radio {
        display: flex;
        align-items: flex-start;
        gap: .4rem;
        font-size: .82rem;
        color: #334155;
        cursor: pointer
    }

    .an-radio input[type="radio"] {
        margin-top: 3px
    }

    .an-email-note {
        font-size: .74rem;
        color: #94a3b8;
        margin-top: .3rem
    }

    /* ── Detail Modal (centered) ── */
    .an-detail-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, .5);
        z-index: 9000;
        display: none;
        align-items: center;
        justify-content: center;
        animation: anFadeIn .2s
    }

    .an-detail-overlay.show {
        display: flex
    }

    .an-detail-panel {
        background: #fff;
        width: 560px;
        max-width: 95vw;
        max-height: 90vh;
        overflow-y: auto;
        border-radius: .65rem;
        box-shadow: 0 20px 60px rgba(15, 23, 42, .22);
        animation: anSlideUp .25s ease;
        padding: 0
    }

    .an-detail-head {
        display: flex;
        align-items: center;
        gap: .7rem;
        padding: 1rem 1.15rem .7rem;
        border-bottom: 1px solid #edf2f7
    }

    .an-detail-close {
        background: none;
        border: none;
        font-size: 1.2rem;
        color: #94a3b8;
        cursor: pointer;
        margin-left: auto
    }

    .an-detail-close:hover {
        color: #0f172a
    }

    .an-detail-body {
        padding: 1rem 1.15rem
    }

    .an-detail-content {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: .45rem;
        padding: .85rem .95rem;
        margin-bottom: 1rem;
        font-size: .9rem;
        line-height: 1.55;
        color: #334155
    }

    .an-detail-attach {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: .45rem;
        padding: .75rem .85rem;
        margin-bottom: 1rem
    }

    .an-detail-attach h5 {
        font-size: .88rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 .55rem;
        display: flex;
        align-items: center;
        gap: .35rem
    }

    .an-detail-attach-grid {
        display: flex;
        flex-wrap: wrap;
        gap: .55rem
    }

    .an-detail-thumb {
        width: 150px;
        height: 110px;
        border-radius: .55rem;
        overflow: hidden;
        cursor: pointer;
        position: relative;
        border: 1px solid #e2e8f0
    }

    .an-detail-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block
    }

    .an-detail-thumb video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        background: #000
    }

    .an-detail-thumb .att-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        display: flex;
        align-items: center;
        gap: .3rem;
        padding: .25rem .4rem;
        background: linear-gradient(transparent, rgba(0, 0, 0, .55));
        color: #fff;
        font-size: .68rem;
        font-weight: 600
    }

    .an-detail-thumb .att-overlay-top {
        position: absolute;
        top: .35rem;
        left: .35rem;
        display: flex;
        align-items: center;
        gap: .2rem;
        background: rgba(255, 255, 255, .92);
        color: #334155;
        font-size: .65rem;
        font-weight: 600;
        padding: .15rem .35rem;
        border-radius: .25rem;
        line-height: 1
    }

    .an-detail-thumb .att-play {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(0, 0, 0, .2)
    }

    .an-detail-thumb .att-play i {
        font-size: 1.8rem;
        color: #fff;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, .3))
    }

    .an-detail-thumb .att-duration {
        position: absolute;
        bottom: .35rem;
        right: .35rem;
        background: rgba(0, 0, 0, .7);
        color: #fff;
        font-size: .62rem;
        font-weight: 600;
        padding: .12rem .3rem;
        border-radius: .2rem
    }

    .an-detail-pdf {
        width: 150px;
        height: 110px;
        border-radius: .55rem;
        border: 1px solid #fecaca;
        background: #fff5f5;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: .5rem .4rem;
        cursor: pointer;
        text-align: center;
        position: relative
    }

    .an-detail-pdf .pdf-badge {
        position: absolute;
        top: .35rem;
        left: .35rem;
        background: #ef4444;
        color: #fff;
        font-size: .58rem;
        font-weight: 700;
        border-radius: .2rem;
        padding: .1rem .3rem
    }

    .an-detail-pdf i {
        font-size: 2rem;
        color: #ef4444;
        margin-bottom: .2rem
    }

    .an-detail-pdf span {
        font-size: .72rem;
        color: #ef4444;
        font-weight: 600;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        max-width: 120px
    }

    /* ── Lightbox ── */
    .an-lightbox {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, .88);
        z-index: 9800;
        display: none;
        align-items: center;
        justify-content: center;
        animation: anFadeIn .2s
    }

    .an-lightbox.show {
        display: flex
    }

    .an-lightbox img,
    .an-lightbox video {
        max-width: 92vw;
        max-height: 90vh;
        border-radius: .4rem;
        box-shadow: 0 8px 40px rgba(0, 0, 0, .5);
        object-fit: contain
    }

    .an-lightbox video {
        background: #000
    }

    .an-lightbox-close {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: rgba(255, 255, 255, .15);
        border: 1px solid rgba(255, 255, 255, .25);
        color: #fff;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        cursor: pointer;
        transition: background .2s
    }

    .an-lightbox-close:hover {
        background: rgba(255, 255, 255, .3)
    }

    /* ── Like & Comment Sections ── */
    .an-section {
        border: 1px solid #e2e8f0;
        border-radius: .45rem;
        padding: .75rem .85rem;
        margin-bottom: .85rem;
        background: #fff
    }

    .an-section h5 {
        font-size: .88rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 .5rem;
        display: flex;
        align-items: center;
        gap: .35rem
    }

    .an-like-btn {
        border: 1px solid #d0d8e2;
        background: #fff;
        color: #334155;
        border-radius: .38rem;
        padding: .4rem .65rem;
        font-size: .82rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        float: right;
        margin-top: -.3rem
    }

    .an-like-btn.liked {
        background: #dbeafe;
        border-color: #93c5fd;
        color: #2563eb;
        font-weight: 600
    }

    .an-like-btn:hover {
        background: #f1f5f9
    }

    .an-no-content {
        color: #94a3b8;
        font-size: .82rem
    }

    .an-comment-item {
        display: flex;
        gap: .55rem;
        padding: .5rem 0;
        border-bottom: 1px solid #f1f5f9
    }

    .an-comment-item:last-child {
        border-bottom: none
    }

    .an-comment-author {
        font-size: .82rem;
        font-weight: 600;
        color: #0f172a
    }

    .an-comment-time {
        font-size: .72rem;
        color: #94a3b8
    }

    .an-comment-text {
        font-size: .84rem;
        color: #334155;
        margin-top: .1rem
    }

    .an-comment-input-wrap {
        display: flex;
        gap: .4rem;
        align-items: center;
        padding-top: .6rem;
        border-top: 1px solid #e2e8f0;
        margin-top: .5rem
    }

    .an-comment-input {
        flex: 1;
        border: 1px solid #d0d8e2;
        border-radius: .38rem;
        padding: .45rem .6rem;
        font-size: .84rem;
        font-family: inherit;
        outline: none
    }

    .an-comment-input:focus {
        border-color: #4b5f78;
        box-shadow: 0 0 0 3px rgba(75, 95, 120, .1)
    }

    .an-comment-send {
        background: none;
        border: none;
        color: #4b5f78;
        font-size: 1rem;
        cursor: pointer;
        padding: .3rem
    }

    .an-comment-send:hover {
        color: #334155
    }

    /* ── Kebab Menu ── */
    .an-kebab-wrap {
        position: relative;
        margin-left: auto
    }

    .an-kebab-btn {
        background: none;
        border: none;
        color: #94a3b8;
        font-size: 1.15rem;
        cursor: pointer;
        padding: .3rem .15rem;
        border-radius: .3rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color .15s, background .15s
    }

    .an-kebab-btn:hover,
    .an-kebab-btn.active {
        color: #0f172a;
        background: #f1f5f9
    }

    .an-kebab-menu {
        position: absolute;
        top: 100%;
        right: 0;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: .45rem;
        box-shadow: 0 8px 24px rgba(15, 23, 42, .12);
        min-width: 150px;
        padding: .3rem 0;
        z-index: 10;
        display: none;
        animation: anFadeIn .12s
    }

    .an-kebab-menu.show {
        display: block
    }

    .an-kebab-menu button {
        width: 100%;
        border: none;
        background: none;
        padding: .48rem .75rem;
        text-align: left;
        font-size: .86rem;
        color: #334155;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: .4rem
    }

    .an-kebab-menu button:hover {
        background: #f8fafc
    }

    .an-kebab-menu button.danger {
        color: #ef4444
    }

    .an-kebab-menu button.danger:hover {
        background: #fef2f2
    }

    .an-kebab-divider {
        height: 1px;
        background: #e2e8f0;
        margin: .2rem 0
    }

    .an-edit-btn {
        border: 1px solid #d0d8e2;
        background: #fff;
        color: #334155;
        border-radius: .38rem;
        padding: .4rem .65rem;
        font-size: .82rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: .3rem
    }

    .an-edit-btn:hover {
        background: #f1f5f9
    }

    /* ── Filename Dialog ── */
    .an-name-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, .45);
        z-index: 9500;
        display: none;
        align-items: center;
        justify-content: center
    }

    .an-name-overlay.show {
        display: flex
    }

    .an-name-dialog {
        background: #fff;
        border-radius: .55rem;
        box-shadow: 0 12px 36px rgba(15, 23, 42, .2);
        width: 380px;
        max-width: 90vw;
        padding: 1.2rem 1.3rem;
        animation: anSlideUp .2s ease
    }

    .an-name-dialog h4 {
        margin: 0 0 .15rem;
        font-size: 1rem;
        font-weight: 700;
        color: #0f172a
    }

    .an-name-dialog p {
        margin: 0 0 .75rem;
        font-size: .82rem;
        color: #64748b
    }

    .an-name-dialog label {
        font-size: .82rem;
        font-weight: 600;
        color: #334155;
        display: block;
        margin-bottom: .25rem
    }

    .an-name-dialog input {
        width: 100%;
        border: 1px solid #d0d8e2;
        border-radius: .38rem;
        padding: .45rem .6rem;
        font-size: .88rem;
        font-family: inherit;
        outline: none
    }

    .an-name-dialog input:focus {
        border-color: #4b5f78;
        box-shadow: 0 0 0 3px rgba(75, 95, 120, .1)
    }

    .an-name-file-info {
        font-size: .74rem;
        color: #94a3b8;
        margin: .3rem 0 .8rem
    }

    .an-name-actions {
        display: flex;
        justify-content: flex-end;
        gap: .4rem
    }

    @media(max-width:1100px) {
        .an-layout {
            grid-template-columns: 1fr
        }
    }

    @media(max-width:600px) {
        .an-detail-panel {
            width: 100vw;
            max-width: 100vw;
            border-radius: 0
        }
    }
</style>

<!-- ── Hero ── -->
<div class="cc-hero">
    <div class="cc-hero-left">
        <h2 class="cc-hero-title">Anuncios</h2>
        <div class="cc-hero-divider"></div>
        <div class="cc-hero-breadcrumb">
            <i class="bi bi-megaphone"></i>
            <i class="bi bi-chevron-right"></i>
            Comunicaciones Comunitarias
        </div>
    </div>
    <div class="cc-hero-right">
        <button type="button" class="cc-hero-btn" id="an-new-btn">
            <i class="bi bi-plus-lg"></i> Nueva Comunicación
        </button>
    </div>
</div>
<!-- ── Hero END── -->

<!-- ── Layout ── -->
<div class="an-layout">
    <aside class="an-sidebar">
        <h3><i class="bi bi-funnel me-1"></i> Filtrar por Categoría</h3>
        <div class="label">Buscar comunicaciones...</div>
        <div class="an-search-wrap"><i class="bi bi-search"></i><input type="text" class="an-search" id="an-search"
                placeholder="Buscar comunicaciones..."></div>
        <div class="label">Filtrar por Categoría</div>
        <div class="an-cat-list">
            <?php foreach ($categoryMeta as $k => $m): ?>
                <button type="button" class="an-cat-btn <?= $k === 'all' ? 'active' : '' ?>" data-category="<?= esc($k) ?>">
                    <span class="left"><i class="bi <?= esc($m['icon']) ?>"></i> <?= esc($m['label']) ?></span>
                    <span class="count"><?= esc((string) ($counts[$k] ?? 0)) ?></span>
                </button>
            <?php endforeach; ?>
        </div>
        <div class="label">Ordenar Por</div>
        <select id="an-order" class="an-order">
            <option value="recent">Más Recientes</option>
            <option value="oldest">Más Antiguos</option>
        </select>
    </aside>

    <section class="an-main">
        <?php if (empty($rawAnnouncements)): ?>
            <div class="an-empty">
                <div><i class="bi bi-megaphone"></i>No hay comunicaciones publicadas todavía.</div>
            </div>
        <?php else: ?>
            <div class="an-feed" id="an-feed">
                <?php foreach ($rawAnnouncements as $a):
                    $cat = $a['category'] ?? 'general';
                    $catLabel = $categoryMeta[$cat]['label'] ?? 'General';
                    $fn = trim(($a['first_name'] ?? '') . ' ' . ($a['last_name'] ?? ''));
                    if ($fn === '')
                        $fn = 'Administrador';
                    $ini = strtoupper(mb_substr($a['first_name'] ?? '', 0, 1) . mb_substr($a['last_name'] ?? '', 0, 1));
                    if ($ini === '')
                        $ini = 'AD';
                    $ts = strtotime($a['created_at'] ?? 'now');
                    $rel = $toRel($ts);
                    $coverUrl = $a['cover_file'] ? base_url('admin/anuncios/archivo/' . $a['cover_file']) : '';
                    ?>
                    <article class="an-card an-item" data-id="<?= esc((string) $a['id']) ?>" data-category="<?= esc($cat) ?>"
                        data-created="<?= esc((string) $ts) ?>">
                        <?php if ($coverUrl): ?><img class="an-card-cover" src="<?= esc($coverUrl) ?>"
                                alt="cover"><?php endif; ?>
                        <div class="an-card-head">
                            <span class="an-avatar"><?= esc($ini) ?></span>
                            <div>
                                <div class="an-author"><?= esc($fn) ?></div>
                                <div class="an-meta">Admin • <?= esc($rel) ?></div>
                            </div>
                            <span class="an-badge"><?= esc($catLabel) ?></span>
                            <div class="an-card-kebab">
                                <button class="an-kebab-btn an-card-kebab-btn" data-id="<?= esc((string) $a['id']) ?>"
                                    title="Opciones" onclick="event.stopPropagation()"><i
                                        class="bi bi-three-dots-vertical"></i></button>
                                <div class="an-kebab-menu an-card-kebab-menu">
                                    <button type="button" class="an-card-edit-btn" data-id="<?= esc((string) $a['id']) ?>"
                                        onclick="event.stopPropagation()"><i class="bi bi-pencil"></i> Editar</button>
                                    <div class="an-kebab-divider"></div>
                                    <button type="button" class="danger an-card-delete-btn"
                                        data-id="<?= esc((string) $a['id']) ?>" onclick="event.stopPropagation()"><i
                                            class="bi bi-trash3"></i> Eliminar</button>
                                </div>
                            </div>
                        </div>
                        <div class="an-card-body">
                            <p class="an-snippet">
                                <?= esc(html_entity_decode(strip_tags($a['content'] ?? ''), ENT_QUOTES, 'UTF-8')) ?>
                            </p>
                        </div>
                        <div class="an-footer">
                            <span class="an-foot-item"><i class="bi bi-hand-thumbs-up"></i>
                                <?= esc((string) ($a['like_count'] ?? 0)) ?></span>
                            <span class="an-foot-item"><i class="bi bi-chat"></i>
                                <?= esc((string) ($a['comment_count'] ?? 0)) ?></span>
                            <span class="an-foot-item"><i class="bi bi-paperclip"></i>
                                <?= esc((string) ($a['attach_count'] ?? 0)) ?></span>
                            <span class="an-foot-item"><i class="bi bi-eye"></i>
                                <?= esc((string) ($a['view_count'] ?? 0)) ?></span>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
            <div class="an-empty d-none" id="an-empty-filtered">
                <div><i class="bi bi-search"></i>No encontramos comunicaciones con los filtros actuales.</div>
            </div>
        <?php endif; ?>
    </section>
</div>

<!-- ── Modal: Nueva Comunicación ── -->
<div class="an-modal-overlay" id="an-modal-overlay">
    <div class="an-modal" id="an-modal">
        <div class="an-modal-header">
            <div>
                <h3>Nueva comunicación</h3>
                <p>Crea una comunicación importante para los residentes de tu condominio.</p>
            </div>
            <div style="display:flex;align-items:center">
                <button type="button" class="an-gear-btn" id="an-gear-btn" title="Opciones de envío"><i
                        class="bi bi-gear"></i></button>
                <button type="button" class="an-modal-close" id="an-modal-close">&times;</button>
            </div>
        </div>
        <div class="an-modal-body">
            <div class="an-modal-left">
                <div class="label" style="font-size:.82rem;font-weight:600;color:#334155;margin-bottom:.35rem">Categoría
                </div>
                <div class="an-cat-chips" id="an-cat-chips">
                    <button type="button" class="an-cat-chip active" data-cat="general"><i class="bi bi-megaphone"></i>
                        General</button>
                    <button type="button" class="an-cat-chip" data-cat="maintenance"><i class="bi bi-wrench"></i>
                        Mantenimiento</button>
                    <button type="button" class="an-cat-chip" data-cat="urgent"><i
                            class="bi bi-exclamation-triangle"></i> Urgente</button>
                    <button type="button" class="an-cat-chip" data-cat="event"><i class="bi bi-calendar-event"></i>
                        Evento</button>
                </div>
                <div class="label" style="font-size:.82rem;font-weight:600;color:#334155;margin-bottom:.25rem">Mensaje
                </div>
                <div class="an-editor-wrap">
                    <div class="an-editor-toolbar">
                        <button type="button" title="Título" data-cmd="formatBlock" data-val="H3"><b>T</b></button>
                        <button type="button" title="Negrita" data-cmd="bold"><b>B</b></button>
                        <button type="button" title="Cursiva" data-cmd="italic"><i>I</i></button>
                        <button type="button" title="Tachado" data-cmd="strikeThrough"><s>S</s></button>
                        <button type="button" title="Enlace" data-cmd="createLink"><i
                                class="bi bi-link-45deg"></i></button>
                        <button type="button" title="Lista" data-cmd="insertUnorderedList"><i
                                class="bi bi-list-ul"></i></button>
                        <button type="button" title="Lista numérica" data-cmd="insertOrderedList"><i
                                class="bi bi-list-ol"></i></button>
                        <button type="button" title="Cita" data-cmd="formatBlock" data-val="BLOCKQUOTE"><i
                                class="bi bi-blockquote-left"></i></button>
                        <button type="button" title="Código" data-cmd="formatBlock" data-val="PRE">&lt;/&gt;</button>
                    </div>
                    <div class="an-editor-content" id="an-editor" contenteditable="true"
                        data-placeholder="Nuevo anuncio para residentes"></div>
                </div>
                <div class="an-file-section">
                    <div class="label" style="font-size:.82rem;font-weight:600;color:#334155;margin-bottom:.25rem">
                        Archivos adjuntos</div>
                    <div class="an-file-btns">
                        <button type="button" class="an-file-btn" id="an-add-img"><i class="bi bi-card-image"></i>
                            Agregar imágenes</button>
                        <button type="button" class="an-file-btn" id="an-add-vid"><i class="bi bi-camera-video"></i>
                            Agregar video</button>
                        <button type="button" class="an-file-btn" id="an-add-pdf"><i class="bi bi-file-earmark-pdf"></i>
                            Agregar PDF</button>
                    </div>
                    <div class="an-file-chips" id="an-file-chips"></div>
                    <div class="an-file-limit" id="an-file-limit"></div>
                    <input type="file" id="an-file-img" accept="image/*" multiple style="display:none">
                    <input type="file" id="an-file-vid" accept="video/*" style="display:none">
                    <input type="file" id="an-file-pdf" accept="application/pdf" style="display:none">
                </div>
            </div>
            <div class="an-modal-right" id="an-modal-right">
                <div style="font-size:.88rem;font-weight:700;color:#0f172a;margin-bottom:.5rem"><i
                        class="bi bi-envelope"></i> Envío por correo</div>
                <label class="an-toggle"><input type="checkbox" id="an-send-email"><span class="an-toggle-track"></span>
                    Enviar también por correo</label>
                <p style="font-size:.76rem;color:#64748b;margin:.15rem 0 .55rem">Esta publicación se compartirá en el
                    muro y también se enviará por correo electrónico a:</p>
                <div class="an-radio-group" id="an-email-targets" style="display:none">
                    <label class="an-radio"><input type="radio" name="email_target" value="owners" checked>
                        <span><strong>Solo propietarios</strong></span></label>
                    <label class="an-radio"><input type="radio" name="email_target" value="all"> <span><strong>Todos en
                                la comunidad</strong> (propietarios y arrendatarios)</span></label>
                </div>
                <p class="an-email-note" id="an-email-note" style="display:none">Se enviará a los correos registrados en
                    las unidades.</p>
            </div>
        </div>
        <div class="an-modal-footer">
            <button type="button" class="cc-btn" id="an-modal-cancel">Cancelar</button>
            <button type="button" class="cc-btn primary" id="an-modal-submit"><i class="bi bi-send"></i> Enviar</button>
        </div>
    </div>
</div>

<!-- ── Filename Dialog ── -->
<div class="an-name-overlay" id="an-name-overlay">
    <div class="an-name-dialog">
        <h4 id="an-name-title">Nombre del documento PDF</h4>
        <p>Por favor, ingrese un nombre descriptivo para este documento PDF. Este nombre será visible para los
            residentes.</p>
        <label>Nombre del documento *</label>
        <input type="text" id="an-name-input">
        <div class="an-name-file-info" id="an-name-fileinfo"></div>
        <div class="an-name-actions">
            <button type="button" class="cc-btn" id="an-name-cancel">Cancelar</button>
            <button type="button" class="cc-btn primary" id="an-name-confirm">Confirmar</button>
        </div>
    </div>
</div>

<!-- ── Lightbox (image + video) ── -->
<div class="an-lightbox" id="an-lightbox">
    <img src="" alt="preview" id="an-lightbox-img" style="display:none">
    <video src="" id="an-lightbox-vid" controls style="display:none"></video>
    <button class="an-lightbox-close" id="an-lightbox-close">&times;</button>
</div>

<!-- ── Detail Modal (centered) ── -->
<div class="an-detail-overlay" id="an-detail-overlay">
    <div class="an-detail-panel" id="an-detail-panel">
        <div class="an-detail-head">
            <span class="an-avatar" id="an-det-avatar">DO</span>
            <div>
                <div class="an-author" id="an-det-author">Autor</div>
                <div class="an-meta" id="an-det-meta">Admin • hace 1 min</div>
            </div>
            <button class="an-edit-btn" id="an-det-edit"><i class="bi bi-pencil"></i> Editar</button>
            <button class="an-detail-close" id="an-detail-close">&times;</button>
        </div>
        <div class="an-detail-body">
            <div class="an-detail-content" id="an-det-content"></div>
            <div class="an-detail-attach" id="an-det-attach-wrap" style="display:none">
                <h5><i class="bi bi-paperclip"></i> Archivos adjuntos <span id="an-det-attach-count">0</span></h5>
                <div class="an-detail-attach-grid" id="an-det-attach-grid"></div>
            </div>
            <div class="an-section">
                <h5><i class="bi bi-hand-thumbs-up"></i> Likes (<span id="an-det-like-count">0</span>)
                    <button class="an-like-btn" id="an-det-like-btn" style="margin-left:auto"><i
                            class="bi bi-hand-thumbs-up"></i> Like</button>
                </h5>
                <div id="an-det-like-text" class="an-no-content">No hay likes aún</div>
            </div>
            <div class="an-section">
                <h5><i class="bi bi-chat"></i> Comentarios (<span id="an-det-comment-count">0</span>)</h5>
                <div id="an-det-comments"></div>
                <div class="an-comment-input-wrap">
                    <span class="an-avatar" style="width:30px;height:30px;font-size:.7rem">DO</span>
                    <input type="text" class="an-comment-input" id="an-det-comment-input"
                        placeholder="Escribe un comentario...">
                    <button class="an-comment-send" id="an-det-comment-send"><i class="bi bi-send"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        var BASE = '<?= base_url("admin/anuncios") ?>';

        /* ─── State ─── */
        var selectedCategory = 'general', pendingFiles = [], displayNames = {}, activeCategory = 'all', currentDetailId = null;

        /* ─── DOM shortcuts ─── */
        var $ = function (s) { return document.getElementById(s) };
        var overlay = $('an-modal-overlay'), modal = $('an-modal'), editor = $('an-editor');
        var detailOverlay = $('an-detail-overlay'), nameOverlay = $('an-name-overlay');

        /* ─── Modal open/close ─── */
        $('an-new-btn').addEventListener('click', function () {
            overlay.classList.add('show');
            editor.innerHTML = ''; pendingFiles = []; displayNames = {}; selectedCategory = 'general';
            modal.dataset.editId = '';
            document.querySelector('.an-modal-header h3').textContent = 'Nueva comunicaci\u00f3n';
            document.querySelector('.an-modal-header p').textContent = 'Crea una comunicaci\u00f3n importante para los residentes de tu condominio.';
            $('an-modal-submit').innerHTML = '<i class="bi bi-send"></i> Enviar';
            document.querySelectorAll('.an-cat-chip').forEach(function (c) { c.classList.toggle('active', c.dataset.cat === 'general') });
            renderFileChips();
        });
        $('an-modal-close').addEventListener('click', function () { overlay.classList.remove('show'); modal.classList.remove('expanded'); $('an-gear-btn').classList.remove('active') });
        $('an-modal-cancel').addEventListener('click', function () { overlay.classList.remove('show'); modal.classList.remove('expanded'); $('an-gear-btn').classList.remove('active') });
        overlay.addEventListener('click', function (e) { if (e.target === overlay) { overlay.classList.remove('show'); modal.classList.remove('expanded'); $('an-gear-btn').classList.remove('active') } });

        /* ─── Gear toggle ─── */
        $('an-gear-btn').addEventListener('click', function () { modal.classList.toggle('expanded'); this.classList.toggle('active') });
        $('an-send-email').addEventListener('change', function () {
            $('an-email-targets').style.display = this.checked ? 'flex' : 'none';
            $('an-email-note').style.display = this.checked ? 'block' : 'none';
        });

        /* ─── Category chips ─── */
        document.querySelectorAll('.an-cat-chip').forEach(function (c) {
            c.addEventListener('click', function () {
                document.querySelectorAll('.an-cat-chip').forEach(function (x) { x.classList.remove('active') });
                c.classList.add('active'); selectedCategory = c.dataset.cat;
            });
        });

        /* ─── Rich text toolbar ─── */
        document.querySelectorAll('.an-editor-toolbar button').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault(); editor.focus();
                var cmd = btn.dataset.cmd, val = btn.dataset.val || null;
                if (cmd === 'createLink') { val = prompt('URL:', 'https://'); if (!val) return }
                document.execCommand(cmd, false, val);
            });
        });

        /* ─── File uploads ─── */
        $('an-add-img').addEventListener('click', function () { if (pendingFiles.length >= 5) { Swal.fire({ toast: true, position: 'top-end', icon: 'warning', title: 'Máximo 5 archivos', showConfirmButton: false, timer: 2000 }); return } $('an-file-img').click() });
        $('an-add-vid').addEventListener('click', function () { if (pendingFiles.length >= 5) { Swal.fire({ toast: true, position: 'top-end', icon: 'warning', title: 'Máximo 5 archivos', showConfirmButton: false, timer: 2000 }); return } $('an-file-vid').click() });
        $('an-add-pdf').addEventListener('click', function () { if (pendingFiles.length >= 5) { Swal.fire({ toast: true, position: 'top-end', icon: 'warning', title: 'Máximo 5 archivos', showConfirmButton: false, timer: 2000 }); return } $('an-file-pdf').click() });

        $('an-file-img').addEventListener('change', function () { addFiles(this.files, 'image'); this.value = '' });
        $('an-file-vid').addEventListener('change', function () { addFiles(this.files, 'video'); this.value = '' });
        $('an-file-pdf').addEventListener('change', function () {
            var file = this.files[0]; if (!file) return; this.value = '';
            // Show naming dialog
            $('an-name-title').textContent = 'Nombre del documento PDF';
            $('an-name-input').value = file.name.replace(/\.pdf$/i, '');
            $('an-name-fileinfo').textContent = 'Archivo: ' + file.name;
            nameOverlay.classList.add('show');
            $('an-name-input').focus(); $('an-name-input').select();
            var confirmHandler = function () {
                var name = $('an-name-input').value.trim() || file.name.replace(/\.pdf$/i, '');
                nameOverlay.classList.remove('show');
                var idx = pendingFiles.length; pendingFiles.push(file); displayNames[idx] = name;
                renderFileChips();
                $('an-name-confirm').removeEventListener('click', confirmHandler);
            };
            $('an-name-confirm').addEventListener('click', confirmHandler);
            $('an-name-cancel').onclick = function () { nameOverlay.classList.remove('show') };
        });

        function addFiles(fileList, type) {
            for (var i = 0; i < fileList.length; i++) {
                if (pendingFiles.length >= 5) break;
                var idx = pendingFiles.length;
                pendingFiles.push(fileList[i]);
                displayNames[idx] = fileList[i].name.replace(/\.[^/.]+$/, '');
            }
            renderFileChips();
        }

        function renderFileChips() {
            var wrap = $('an-file-chips'); wrap.innerHTML = '';
            pendingFiles.forEach(function (f, i) {
                var icon = 'bi-card-image';
                if (f.type.startsWith('video/')) icon = 'bi-camera-video';
                if (f.type === 'application/pdf') icon = 'bi-file-earmark-pdf';
                var chip = document.createElement('span'); chip.className = 'an-file-chip';
                chip.innerHTML = '<i class="bi ' + icon + '"></i> ' + (displayNames[i] || f.name) + ' <button data-idx="' + i + '">&times;</button>';
                wrap.appendChild(chip);
            });
            wrap.querySelectorAll('button').forEach(function (b) {
                b.addEventListener('click', function () {
                    var idx = +this.dataset.idx; pendingFiles.splice(idx, 1);
                    var newNames = {}; Object.keys(displayNames).forEach(function (k) {
                        var ki = +k; if (ki < idx) newNames[ki] = displayNames[ki];
                        else if (ki > idx) newNames[ki - 1] = displayNames[ki];
                    });
                    displayNames = newNames; renderFileChips();
                });
            });
            $('an-file-limit').textContent = pendingFiles.length > 0 ? pendingFiles.length + '/5 archivos' : '';
        }

        /* ─── Submit (create / edit) ─── */
        $('an-modal-submit').addEventListener('click', function () {
            var content = editor.innerHTML.trim();
            if (!content || content === '<br>') { Swal.fire({ toast: true, position: 'top-end', icon: 'warning', title: 'El mensaje es obligatorio', showConfirmButton: false, timer: 2500 }); return }
            var fd = new FormData();
            fd.append('content', content);
            fd.append('category', selectedCategory);
            fd.append('send_email', $('an-send-email').checked ? 1 : 0);
            var target = document.querySelector('input[name="email_target"]:checked');
            fd.append('email_target', target ? target.value : 'owners');
            var names = [];
            pendingFiles.forEach(function (f, i) { fd.append('attachments[]', f); names.push(displayNames[i] || f.name.replace(/\.[^/.]+$/, '')) });
            fd.append('display_names', JSON.stringify(names));

            var editId = modal.dataset.editId;
            var url = editId ? BASE + '/actualizar/' + editId : BASE + '/crear';

            $('an-modal-submit').disabled = true;
            fetch(url, { method: 'POST', body: fd }).then(function (r) { return r.json() }).then(function (d) {
                $('an-modal-submit').disabled = false;
                if (d.status === 200 || d.status === 201) {
                    overlay.classList.remove('show'); modal.classList.remove('expanded');
                    modal.dataset.editId = '';
                    Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: d.message || 'Guardado correctamente', showConfirmButton: false, timer: 2500 });
                    setTimeout(function () { location.reload() }, 800);
                } else {
                    Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: d.error || 'Error', showConfirmButton: false, timer: 3000 });
                }
            }).catch(function () { $('an-modal-submit').disabled = false; Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'Error de conexión', showConfirmButton: false, timer: 3000 }) });
        });

        /* ─── Sidebar filter/sort ─── */
        var catBtns = Array.from(document.querySelectorAll('.an-cat-btn'));
        var cards = Array.from(document.querySelectorAll('.an-item'));
        var feed = $('an-feed'), emptyFilt = $('an-empty-filtered'), searchEl = $('an-search'), orderEl = $('an-order');

        function applyFilters() {
            var term = (searchEl ? searchEl.value : '').toLowerCase().trim(); var vis = 0;
            cards.forEach(function (c) {
                var matchCat = activeCategory === 'all' || c.dataset.category === activeCategory;
                var txt = (c.textContent || '').toLowerCase();
                var matchSearch = !term || txt.indexOf(term) >= 0;
                var show = matchCat && matchSearch; c.style.display = show ? '' : 'none'; if (show) vis++;
            });
            if (feed) { feed.classList.toggle('d-none', vis === 0) }
            if (emptyFilt) { emptyFilt.classList.toggle('d-none', vis > 0) }
        }
        function sortCards(order) {
            if (!feed) return;
            var s = { recent: function (a, b) { return +(b.dataset.created || 0) - +(a.dataset.created || 0) }, oldest: function (a, b) { return +(a.dataset.created || 0) - +(b.dataset.created || 0) } };
            cards.sort(s[order] || s.recent); cards.forEach(function (c) { feed.appendChild(c) });
        }
        catBtns.forEach(function (b) { b.addEventListener('click', function () { activeCategory = b.dataset.category || 'all'; catBtns.forEach(function (x) { x.classList.remove('active') }); b.classList.add('active'); applyFilters() }) });
        if (searchEl) searchEl.addEventListener('input', applyFilters);
        if (orderEl) orderEl.addEventListener('change', function () { sortCards(this.value); applyFilters() });

        /* ─── Card click → detail panel ─── */
        cards.forEach(function (c) {
            c.addEventListener('click', function () { openDetail(+c.dataset.id) });
        });

        function openDetail(id) {
            currentDetailId = id;
            fetch(BASE + '/detalle/' + id).then(function (r) { return r.json() }).then(function (d) {
                if (d.status !== 200) return; var a = d.data;
                var fn = (a.first_name || '') + ' ' + (a.last_name || ''); fn = fn.trim() || 'Administrador';
                var ini = (a.first_name || '').charAt(0).toUpperCase() + (a.last_name || '').charAt(0).toUpperCase(); if (!ini) ini = 'AD';
                $('an-det-avatar').textContent = ini;
                $('an-det-author').textContent = fn;
                var ts = new Date(a.created_at);
                var rel = timeAgo(ts);
                $('an-det-meta').textContent = 'Admin • ' + rel;
                $('an-det-content').innerHTML = a.content || '';

                // Attachments
                var att = a.attachments || [];
                if (att.length) {
                    $('an-det-attach-wrap').style.display = '';
                    $('an-det-attach-count').textContent = att.length;
                    var grid = $('an-det-attach-grid'); grid.innerHTML = '';
                    att.forEach(function (at) {
                        var url = BASE + '/archivo/' + at.file_name;
                        if (at.file_type === 'image') {
                            var div = document.createElement('div'); div.className = 'an-detail-thumb';
                            div.innerHTML = '<img src="' + url + '" alt="' + esc(at.display_name || at.original_name) + '">' + '<div class="att-overlay-top"><i class="bi bi-image"></i> Image</div>' + '<div class="att-overlay"><i class="bi bi-zoom-in"></i></div>';
                            div.onclick = function (e) { e.stopPropagation(); openLightbox(url) }; grid.appendChild(div);
                        } else if (at.file_type === 'video') {
                            var div = document.createElement('div'); div.className = 'an-detail-thumb';
                            div.innerHTML = '<video src="' + url + '" preload="metadata"></video>' + '<div class="att-play"><i class="bi bi-play-circle-fill"></i></div>' + '<div class="att-duration">0:00</div>';
                            var vid = div.querySelector('video');
                            vid.addEventListener('loadedmetadata', function () { var dur = Math.floor(vid.duration); var m = Math.floor(dur / 60); var s = dur % 60; div.querySelector('.att-duration').textContent = m + ':' + (s < 10 ? '0' : '') + s });
                            (function (videoUrl) { div.onclick = function (e) { e.stopPropagation(); openLightboxVideo(videoUrl) }; })(url); grid.appendChild(div);
                        } else if (at.file_type === 'pdf') {
                            var div = document.createElement('div'); div.className = 'an-detail-pdf';
                            div.innerHTML = '<span class="pdf-badge">PDF</span><i class="bi bi-file-earmark-text"></i><span>' + esc(at.display_name || at.original_name) + '</span>';
                            div.onclick = function (e) { e.stopPropagation(); window.open(url, '_blank') }; grid.appendChild(div);
                        }
                    });
                } else { $('an-det-attach-wrap').style.display = 'none' }

                // Likes
                $('an-det-like-count').textContent = a.like_count || 0;
                $('an-det-like-text').textContent = a.like_count > 0 ? a.like_count + ' persona(s) dieron like' : 'No hay likes aún';
                var likeBtn = $('an-det-like-btn');
                likeBtn.classList.toggle('liked', !!a.user_liked);
                likeBtn.innerHTML = a.user_liked ? '<i class="bi bi-hand-thumbs-up-fill"></i> Te gusta' : '<i class="bi bi-hand-thumbs-up"></i> Like';

                // Comments
                $('an-det-comment-count').textContent = a.comment_count || 0;
                var cWrap = $('an-det-comments'); cWrap.innerHTML = '';
                if (!a.comments || !a.comments.length) {
                    cWrap.innerHTML = '<div style="text-align:center;padding:1.2rem;color:#94a3b8"><i class="bi bi-chat" style="font-size:2rem;display:block;margin-bottom:.4rem"></i>No hay comentarios aún<br><small>¡Sé el primero en comentar!</small></div>';
                } else {
                    a.comments.forEach(function (cm) {
                        var cfn = (cm.first_name || '') + ' ' + (cm.last_name || ''); cfn = cfn.trim() || 'Usuario';
                        var cini = ((cm.first_name || '').charAt(0) + (cm.last_name || '').charAt(0)).toUpperCase();
                        var ctime = timeAgo(new Date(cm.created_at));
                        var item = document.createElement('div'); item.className = 'an-comment-item';
                        item.innerHTML = '<span class="an-avatar" style="width:30px;height:30px;font-size:.7rem">' + esc(cini) + '</span><div style="flex:1"><div><span class="an-comment-author">' + esc(cfn) + '</span> <span class="an-comment-time">' + esc(ctime) + '</span></div><div class="an-comment-text">' + esc(cm.content) + '</div></div>';
                        cWrap.appendChild(item);
                    });
                }

                detailOverlay.classList.add('show');
            });
        }

        /* ─── Like toggle ─── */
        $('an-det-like-btn').addEventListener('click', function (e) {
            e.stopPropagation(); if (!currentDetailId) return;
            fetch(BASE + '/like/' + currentDetailId, { method: 'POST' }).then(function (r) { return r.json() }).then(function (d) {
                if (d.status === 200) {
                    $('an-det-like-count').textContent = d.count;
                    $('an-det-like-text').textContent = d.count > 0 ? d.count + ' persona(s) dieron like' : 'No hay likes aún';
                    var btn = $('an-det-like-btn');
                    btn.classList.toggle('liked', d.liked);
                    btn.innerHTML = d.liked ? '<i class="bi bi-hand-thumbs-up-fill"></i> Te gusta' : '<i class="bi bi-hand-thumbs-up"></i> Like';
                }
            });
        });

        /* ─── Add comment ─── */
        function submitComment() {
            var input = $('an-det-comment-input'); var txt = input.value.trim();
            if (!txt || !currentDetailId) return;
            var fd = new FormData(); fd.append('content', txt);
            fetch(BASE + '/comentar/' + currentDetailId, { method: 'POST', body: fd }).then(function (r) { return r.json() }).then(function (d) {
                if (d.status === 201) { input.value = ''; openDetail(currentDetailId) }
            });
        }
        $('an-det-comment-send').addEventListener('click', submitComment);
        $('an-det-comment-input').addEventListener('keydown', function (e) { if (e.key === 'Enter') { e.preventDefault(); submitComment() } });

        /* ─── Detail close ─── */
        $('an-detail-close').addEventListener('click', function () { detailOverlay.classList.remove('show'); location.reload() });
        detailOverlay.addEventListener('click', function (e) { if (e.target === detailOverlay) { detailOverlay.classList.remove('show'); location.reload() } });

        /* ─── Card kebab menu toggle ─── */
        document.querySelectorAll('.an-card-kebab-btn').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                // Close all other menus first
                document.querySelectorAll('.an-card-kebab-menu.show').forEach(function (m) { if (m !== btn.nextElementSibling) m.classList.remove('show') });
                btn.nextElementSibling.classList.toggle('show');
            });
        });
        document.addEventListener('click', function () { document.querySelectorAll('.an-card-kebab-menu.show').forEach(function (m) { m.classList.remove('show') }) });

        /* ─── Card-level Edit ─── */
        document.querySelectorAll('.an-card-edit-btn').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                btn.closest('.an-card-kebab-menu').classList.remove('show');
                editById(+btn.dataset.id);
            });
        });

        /* ─── Card-level Delete ─── */
        document.querySelectorAll('.an-card-delete-btn').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                btn.closest('.an-card-kebab-menu').classList.remove('show');
                deleteById(+btn.dataset.id);
            });
        });

        /* ─── Detail Edit button ─── */
        var editData = null;
        $('an-det-edit').addEventListener('click', function () {
            if (!currentDetailId) return;
            detailOverlay.classList.remove('show');
            editById(currentDetailId);
        });

        /* ─── Shared: Edit by ID ─── */
        function editById(id) {
            fetch(BASE + '/detalle/' + id).then(function (r) { return r.json() }).then(function (d) {
                if (d.status !== 200) return;
                editData = d.data;
                overlay.classList.add('show');
                modal.dataset.editId = id;
                document.querySelector('.an-modal-header h3').textContent = 'Editar comunicación';
                document.querySelector('.an-modal-header p').textContent = 'Modifica el contenido de esta comunicación.';
                $('an-modal-submit').innerHTML = '<i class="bi bi-check-circle"></i> Guardar cambios';
                editor.innerHTML = editData.content || '';
                var cat = editData.category || 'general';
                selectedCategory = cat;
                document.querySelectorAll('.an-cat-chip').forEach(function (c) { c.classList.toggle('active', c.dataset.cat === cat) });
                $('an-send-email').checked = !!editData.send_email;
                $('an-email-targets').style.display = editData.send_email ? 'flex' : 'none';
                $('an-email-note').style.display = editData.send_email ? 'block' : 'none';
                if (editData.email_target) {
                    var radio = document.querySelector('input[name="email_target"][value="' + editData.email_target + '"]');
                    if (radio) radio.checked = true;
                }
                pendingFiles = []; displayNames = {}; renderFileChips();
            });
        }

        /* ─── Shared: Delete by ID ─── */
        function deleteById(id) {
            Swal.fire({
                title: '¿Eliminar comunicación?',
                text: 'Esta acción no se puede deshacer. Se eliminará el comunicado y todos sus archivos adjuntos.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then(function (result) {
                if (result.isConfirmed) {
                    fetch(BASE + '/eliminar/' + id, { method: 'POST' }).then(function (r) { return r.json() }).then(function (d) {
                        if (d.status === 200) {
                            detailOverlay.classList.remove('show');
                            Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Comunicación eliminada', showConfirmButton: false, timer: 2500 });
                            setTimeout(function () { location.reload() }, 800);
                        } else {
                            Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: d.error || 'Error al eliminar', showConfirmButton: false, timer: 3000 });
                        }
                    });
                }
            });
        }

        /* ─── Lightbox (image) ─── */
        function openLightbox(url) {
            $('an-lightbox-img').src = url; $('an-lightbox-img').style.display = 'block';
            $('an-lightbox-vid').style.display = 'none'; $('an-lightbox-vid').pause();
            $('an-lightbox').classList.add('show');
        }
        /* ─── Lightbox (video) ─── */
        function openLightboxVideo(url) {
            $('an-lightbox-vid').src = url; $('an-lightbox-vid').style.display = 'block';
            $('an-lightbox-img').style.display = 'none';
            $('an-lightbox').classList.add('show');
            $('an-lightbox-vid').play();
        }
        function closeLightbox() {
            $('an-lightbox').classList.remove('show');
            $('an-lightbox-vid').pause(); $('an-lightbox-vid').src = '';
            $('an-lightbox-img').src = '';
        }
        $('an-lightbox-close').addEventListener('click', closeLightbox);
        $('an-lightbox').addEventListener('click', function (e) { if (e.target === $('an-lightbox')) closeLightbox() });

        /* ─── Helpers ─── */
        function esc(s) { var d = document.createElement('div'); d.textContent = s || ''; return d.innerHTML }
        function timeAgo(dt) { var d = Math.max(0, Math.floor((Date.now() - dt.getTime()) / 1000)); if (d < 60) return 'hace unos segundos'; if (d < 3600) return 'hace ' + Math.floor(d / 60) + ' min'; if (d < 86400) return 'hace ' + Math.floor(d / 3600) + ' h'; return 'hace ' + Math.floor(d / 86400) + ' d' }
    })();
</script>
<?= $this->endSection() ?>