<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

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
        color: #238B71;
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

    /* ── end Hero ── */

    .td-hero {
        background: linear-gradient(135deg, #364861 0%, #1f2b42 100%);
        border-radius: 0.6rem;
        padding: 1.55rem;
        color: #fff;
        margin-bottom: 1.5rem;
    }

    .td-hero h2 {
        font-size: 1.5rem;
        margin: 0 0 0.25rem 0;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .td-back-btn {
        background: #1C2434;
        border: none;
        color: white;
        border-radius: 0.35rem;
        padding: 0.25rem 0.6rem;
        transition: 0.2s;
    }

    .td-back-btn:hover {
        background: #727272ff;
    }

    .td-hero p {
        margin: 0 0 0 2.8rem;
        color: #cbd5e1;
        font-size: 0.95rem;
    }

    .td-sidebar {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 0.6rem;
        padding: 1.25rem;
    }

    .td-sidebar h4 {
        font-weight: 700;
        font-size: 1.15rem;
        margin-bottom: 1.25rem;
        color: #0f172a;
    }

    .td-meta-label {
        font-size: 0.72rem;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 0.3rem;
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .td-meta-value {
        font-size: 0.9rem;
        color: #334155;
        margin-bottom: 1.15rem;
        font-weight: 500;
    }

    .td-nav-tabs {
        border-bottom: 1px solid #e2e8f0;
        margin-bottom: 1rem;
    }

    .td-nav-tabs .nav-link {
        color: #64748b;
        font-weight: 600;
        border: none;
        padding: 0.75rem 1.25rem;
        background: transparent;
        transition: color 0.15s ease-in-out;
    }

    .td-nav-tabs .nav-link:hover {
        color: #0f172a !important;
        background: transparent !important;
    }

    .td-nav-tabs .nav-link.active {
        color: #0f172a !important;
        border-bottom: 2px solid #238B71 !important;
        background: transparent !important;
    }

    .td-nav-tabs .nav-link i {
        margin-right: 0.4rem;
    }

    .tab-content>.tab-pane {
        padding-top: 0.25rem;
    }

    .td-panel {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 0.6rem;
        padding: 1.5rem;
        margin-bottom: 1.25rem;
    }

    .td-panel-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
    }

    .td-panel-title {
        font-weight: 700;
        font-size: 1.15rem;
        margin: 0;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .td-panel-badge {
        background: #eff6ff;
        color: #2563eb;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .td-grid-box {
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 0.85rem;
    }

    .td-block-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }

    .td-avatar-circle {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: #eff6ff;
        color: #2563eb;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 0.85rem;
    }

    .td-badge-progreso {
        background: #238B71;
        color: #fff;
        padding: 0.25rem 0.6rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .td-badge-critico {
        background: #ef4444;
        color: #fff;
        padding: 0.25rem 0.6rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .td-timeline {
        border-left: 2px solid #e2e8f0;
        padding-left: 1.25rem;
        margin-left: 0.5rem;
    }

    .td-timeline-item {
        position: relative;
        margin-bottom: 1.25rem;
    }

    .td-timeline-item::before {
        content: '';
        position: absolute;
        left: -1.6rem;
        top: 0.25rem;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #94a3b8;
        border: 2px solid #fff;
    }

    .td-timeline-title {
        font-size: 0.9rem;
        font-weight: 600;
        color: #334155;
        margin: 0;
    }

    .td-timeline-date {
        font-size: 0.75rem;
        color: #94a3b8;
    }

    /* ── Conversation Sub-tabs ── */
    .conv-subtabs {
        display: flex;
        border-bottom: 2px solid #e2e8f0;
        background: #fff;
        border-radius: 0.6rem 0.6rem 0 0;
        overflow: hidden;
    }

    .conv-subtab {
        flex: 1;
        padding: 0.75rem 1rem;
        border: none;
        background: transparent;
        font-weight: 600;
        font-size: 0.875rem;
        color: #64748b;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        transition: 0.2s;
        position: relative;
    }

    .conv-subtab:hover {
        color: #334155;
        background: #f8fafc;
    }

    .conv-subtab.active[data-type="reply"] {
        color: #2563eb;
        background: #eff6ff;
        border-bottom: 2.5px solid #238B71;
    }

    .conv-subtab.active[data-type="internal"] {
        color: #b45309;
        background: #fffbeb;
        border-bottom: 2.5px solid #f59e0b;
    }

    /* ── Context Hint ── */
    .conv-context-hint {
        padding: 0.5rem 1rem;
        font-size: 0.78rem;
        display: flex;
        align-items: center;
        gap: 0.35rem;
        border-left: 1px solid #e2e8f0;
        border-right: 1px solid #e2e8f0;
    }

    .conv-context-hint.mode-reply {
        color: #2563eb;
        background: #eff6ff;
    }

    .conv-context-hint.mode-internal {
        color: #92400e;
        background: #fffbeb;
    }

    /* ── Chat Thread ── */
    .conv-thread {
        background: #fff;
        border-left: 1px solid #e2e8f0;
        border-right: 1px solid #e2e8f0;
        min-height: 180px;
        max-height: 440px;
        overflow-y: auto;
        padding: 1rem 1.25rem;
    }

    .conv-date-divider {
        text-align: center;
        color: #94a3b8;
        font-size: 0.75rem;
        font-weight: 500;
        margin: 0.75rem 0;
        position: relative;
    }

    .conv-date-divider span {
        background: #fff;
        padding: 0 0.75rem;
        position: relative;
        z-index: 1;
    }

    .conv-date-divider::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 1px;
        background: #e2e8f0;
        z-index: 0;
    }

    /* ── Message Bubble ── */
    .conv-msg {
        margin-bottom: 1rem;
    }

    .conv-msg-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.35rem;
    }

    .conv-msg-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.7rem;
        flex-shrink: 0;
    }

    .conv-msg-avatar.reply-avatar {
        background: #dbeafe;
        color: #2563eb;
    }

    .conv-msg-avatar.internal-avatar {
        background: #fef3c7;
        color: #92400e;
    }

    .conv-msg-name {
        font-weight: 600;
        font-size: 0.85rem;
        color: #0f172a;
    }

    .conv-msg-badge {
        font-size: 0.65rem;
        font-weight: 600;
        padding: 0.15rem 0.5rem;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        gap: 0.2rem;
    }

    .conv-msg-badge.badge-admin {
        background: #dbeafe;
        color: #2563eb;
    }

    .conv-msg-badge.badge-internal {
        background: #fef3c7;
        color: #b45309;
    }

    .conv-msg-bubble {
        margin-left: 2.55rem;
        padding: 0.6rem 0.85rem;
        border-radius: 0 12px 12px 12px;
        font-size: 0.875rem;
        line-height: 1.5;
        max-width: 80%;
        word-wrap: break-word;
    }

    .conv-msg-bubble.bubble-reply {
        background: #238B71;
        color: #fff;
    }

    .conv-msg-bubble.bubble-internal {
        background: #fef3c7;
        color: #78350f;
    }

    .conv-msg-media {
        margin-left: 2.55rem;
        margin-top: 0.35rem;
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .conv-msg-media img,
    .conv-msg-media video {
        width: 200px;
        height: 140px;
        object-fit: cover;
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        cursor: pointer;
        transition: 0.2s;
    }

    .conv-msg-media img:hover {
        border-color: #238B71;
        transform: scale(1.02);
    }

    .conv-msg-time {
        margin-left: 2.55rem;
        font-size: 0.7rem;
        color: #94a3b8;
        margin-top: 0.2rem;
    }

    /* ── Quick Responses ── */
    .conv-quick-wrap {
        padding: 0.75rem 1rem;
        background: #fff;
        border-left: 1px solid #e2e8f0;
        border-right: 1px solid #e2e8f0;
        border-top: 1px solid #f1f5f9;
    }

    .conv-quick-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 0.45rem;
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .conv-quick-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 0.4rem;
    }

    .conv-pill {
        border: 1.5px solid #dbeafe;
        background: #f0f7ff;
        color: #2563eb;
        border-radius: 20px;
        padding: 0.3rem 0.75rem;
        font-size: 0.78rem;
        font-weight: 500;
        cursor: pointer;
        transition: 0.2s;
        white-space: nowrap;
    }

    .conv-pill:hover {
        background: #dbeafe;
        border-color: #93c5fd;
    }

    /* ── Message Input ── */
    .conv-input-wrap {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 0 0 0.6rem 0.6rem;
        padding: 0.75rem 1rem;
    }

    .conv-input-row {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .conv-attach-btn {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #64748b;
        transition: 0.2s;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .conv-attach-btn:hover {
        background: #f1f5f9;
        color: #334155;
    }

    .conv-input-field-wrap {
        flex: 1;
        position: relative;
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        overflow: hidden;
        display: flex;
        align-items: center;
        transition: border-color 0.2s;
    }

    .conv-input-field-wrap:focus-within {
        border-color: #93c5fd;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
    }

    .conv-input-field-wrap.mode-internal:focus-within {
        border-color: #fcd34d;
        box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.1);
    }

    .conv-textarea {
        flex: 1;
        border: none;
        outline: none;
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        resize: none;
        max-height: 100px;
        line-height: 1.4;
        background: transparent;
    }

    .conv-char-count {
        font-size: 0.7rem;
        color: #94a3b8;
        padding-right: 0.75rem;
        font-variant-numeric: tabular-nums;
        user-select: none;
    }

    .conv-send-btn {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        cursor: pointer;
        transition: 0.2s;
        flex-shrink: 0;
        color: #fff;
    }

    .conv-send-btn.mode-reply {
        background: #238B71;
    }

    .conv-send-btn.mode-reply:hover {
        background: #2563eb;
    }

    .conv-send-btn.mode-internal {
        background: #f59e0b;
    }

    .conv-send-btn.mode-internal:hover {
        background: #d97706;
    }

    .conv-send-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .conv-hint {
        font-size: 0.7rem;
        color: #94a3b8;
        margin-top: 0.35rem;
        padding-left: 3rem;
    }

    /* ── File Preview ── */
    .conv-file-preview {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-top: 0.5rem;
        padding-left: 3rem;
    }

    .conv-file-thumb {
        position: relative;
        width: 64px;
        height: 64px;
        border-radius: 8px;
        overflow: hidden;
        border: 1.5px solid #e2e8f0;
    }

    .conv-file-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .conv-file-thumb .conv-file-remove {
        position: absolute;
        top: 2px;
        right: 2px;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: rgba(0, 0, 0, 0.6);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.6rem;
        cursor: pointer;
        transition: 0.15s;
        border: none;
    }

    .conv-file-thumb .conv-file-remove:hover {
        background: #ef4444;
    }

    /* ── Image Lightbox ── */
    .lightbox-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.85);
        z-index: 99999;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .lightbox-overlay.active {
        display: flex;
    }

    .lightbox-close {
        position: absolute;
        top: 16px;
        right: 20px;
        z-index: 100001;
        background: rgba(255, 255, 255, 0.15);
        border: none;
        color: #fff;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        cursor: pointer;
        transition: 0.2s;
        backdrop-filter: blur(6px);
    }

    .lightbox-close:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    .lightbox-content {
        max-width: 90vw;
        max-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border-radius: 12px;
    }

    .lightbox-content img {
        max-width: 90vw;
        max-height: 80vh;
        object-fit: contain;
        border-radius: 12px;
        box-shadow: 0 8px 40px rgba(0, 0, 0, 0.6);
        transition: transform 0.2s;
    }

    .lightbox-hint {
        color: rgba(255, 255, 255, 0.5);
        font-size: 0.8rem;
        margin-top: 1rem;
        background: rgba(0, 0, 0, 0.5);
        padding: 0.35rem 1rem;
        border-radius: 20px;
    }

    /* ── Archivos Tab: Files ── */
    .files-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.85rem 1rem;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 0.6rem 0.6rem 0 0;
    }

    .files-header-left {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .files-header-right {
        display: flex;
        align-items: center;
    }

    .files-grid {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-top: none;
        border-radius: 0 0 0.6rem 0.6rem;
        padding: 1rem;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 1rem;
    }

    .files-grid.list-mode {
        grid-template-columns: 1fr;
    }

    /* ── File Card ── */
    .file-card {
        border: 1px solid #e2e8f0;
        border-radius: 0.6rem;
        overflow: hidden;
        background: #fff;
        transition: 0.2s;
    }

    .file-card:hover {
        border-color: #93c5fd;
        box-shadow: 0 2px 12px rgba(59, 130, 246, 0.1);
    }

    .file-card-thumb {
        position: relative;
        width: 100%;
        height: 160px;
        overflow: hidden;
        background: #f8fafc;
        cursor: pointer;
    }

    .file-card-thumb img,
    .file-card-thumb video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: 0.3s;
    }

    .file-card:hover .file-card-thumb img {
        transform: scale(1.03);
    }

    .file-card-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(0deg, rgba(0, 0, 0, 0.65) 0%, transparent 100%);
        padding: 2rem 0.65rem 0.5rem;
        color: #fff;
        font-size: 0.72rem;
        display: flex;
        align-items: flex-end;
        gap: 0.35rem;
        opacity: 0;
        transition: 0.25s;
    }

    .file-card:hover .file-card-overlay {
        opacity: 1;
    }

    .file-card-overlay i {
        font-size: 0.85rem;
    }

    .file-card-info {
        padding: 0.55rem 0.65rem;
    }

    .file-card-uploader {
        font-size: 0.78rem;
        color: #475569;
        font-weight: 500;
        margin-bottom: 0.35rem;
    }

    .file-card-download {
        width: 100%;
        border: none;
        background: transparent;
        border-top: 1px solid #f1f5f9;
        padding: 0.45rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        color: #2563eb;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        transition: 0.2s;
    }

    .file-card-download:hover {
        background: #eff6ff;
    }

    /* ── List mode file card ── */
    .files-grid.list-mode .file-card {
        display: flex;
        flex-direction: row;
        align-items: center;
    }

    .files-grid.list-mode .file-card-thumb {
        width: 80px;
        height: 60px;
        flex-shrink: 0;
        border-radius: 0.4rem;
        margin: 0.5rem;
    }

    .files-grid.list-mode .file-card-overlay {
        display: none;
    }

    .files-grid.list-mode .file-card-info {
        flex: 1;
        padding: 0.4rem 0.5rem;
    }

    .files-grid.list-mode .file-card-download {
        border-top: none;
        border-left: 1px solid #f1f5f9;
        width: auto;
        padding: 0 1rem;
    }

    /* ── Start Work Banner ── */
    .start-work-banner {
        background: linear-gradient(135deg, #238B71 0%, #176350ff 100%);
        border-radius: 0.75rem;
        padding: 0;
        margin-bottom: 1.25rem;
        overflow: hidden;
        cursor: pointer;
        transition: 0.25s;
        box-shadow: 0 4px 16px rgba(51, 65, 85, 0.25);
    }

    .start-work-banner:hover {
        box-shadow: 0 6px 24px rgba(51, 65, 85, 0.4);
        transform: translateY(-1px);
    }

    .start-work-btn-main {
        width: 100%;
        border: none;
        background: transparent;
        color: #fff;
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.6rem;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
    }

    .start-work-btn-main i {
        font-size: 1.3rem;
    }

    .start-work-btn-main .badge {
        background: rgba(255, 255, 255, 0.2);
        font-size: 0.75rem;
        padding: 0.25rem 0.6rem;
        border-radius: 10px;
        font-weight: 600;
    }

    .start-work-hint {
        background: #238B71;
        color: rgba(255, 255, 255, 0.8);
        padding: 0.55rem 1.25rem;
        font-size: 0.8rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
    }

    .start-work-hint i {
        color: #1C2434;
        flex-shrink: 0;
    }

    /* ── Acciones Tab ── */
    .act-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.25rem;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 0.6rem 0.6rem 0 0;
    }

    .act-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }

    .act-status-badge {
        color: #fff;
        font-size: 0.72rem;
        font-weight: 600;
        padding: 0.3rem 0.85rem;
        border-radius: 20px;
    }

    .act-body {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-top: none;
        border-radius: 0 0 0.6rem 0.6rem;
        padding: 1rem 1.25rem;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .act-card {
        border: 1px solid #e2e8f0;
        border-radius: 0.6rem;
        overflow: hidden;
        transition: 0.2s;
        background: #fff;
    }

    .act-card:hover {
        border-color: #cbd5e1;
    }

    .act-card-row {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.85rem 1rem;
        cursor: pointer;
        transition: 0.15s;
    }

    .act-card-row:hover {
        background: #f8fafc;
    }

    .act-card-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        color: #475569;
        flex-shrink: 0;
    }

    .act-card-text {
        flex: 1;
        min-width: 0;
    }

    .act-card-label {
        font-weight: 600;
        font-size: 0.88rem;
        color: #1e293b;
    }

    .act-card-sub {
        font-size: 0.75rem;
        color: #64748b;
        margin-top: 1px;
    }

    .act-card-arrow {
        color: #94a3b8;
        font-size: 0.8rem;
        transition: 0.2s;
    }

    .act-card-row:hover .act-card-arrow {
        transform: translateX(2px);
    }

    /* Featured (blue) card */
    .act-card-featured {
        background: #238B71;
        border-color: #238B71;
    }

    .act-card-featured:hover {
        border-color: #238B71;
        background: #238B71;
    }

    .act-card-featured .act-card-row:hover {
        background: rgba(255, 255, 255, 0.08);
    }

    .act-icon-primary {
        background: rgba(255, 255, 255, 0.2) !important;
        color: #fff !important;
    }

    .act-label-white {
        color: #1e293b;
    }

    .act-sub-white {
        color: #64748b;
    }

    .act-arrow-white {
        color: #94a3b8;
    }

    .act-card-featured .act-label-white {
        color: #fff !important;
    }

    .act-card-featured .act-sub-white {
        color: rgba(255, 255, 255, 0.75) !important;
    }

    .act-card-featured .act-arrow-white {
        color: rgba(255, 255, 255, 0.6) !important;
    }

    /* Expanded form panel */
    .act-expand {
        border-top: 1px solid #e2e8f0;
    }

    .act-card-featured .act-expand {
        background: #fff;
        border-top: none;
    }

    .act-expand-inner {
        padding: 1rem 1.25rem;
    }

    .act-field-label {
        font-weight: 600;
        font-size: 0.82rem;
        color: #1e293b;
    }

    .act-char-counter {
        font-size: 0.72rem;
        color: #94a3b8;
    }

    .act-textarea {
        width: 100%;
        border: 2px solid #e2e8f0;
        border-radius: 0.5rem;
        padding: 0.65rem 0.85rem;
        font-size: 0.85rem;
        color: #334155;
        resize: vertical;
        transition: 0.2s;
        outline: none;
        font-family: inherit;
        min-height: 80px;
    }

    .act-textarea:focus {
        border-color: #238B71;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .act-expand-hint {
        font-size: 0.75rem;
        color: #94a3b8;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .act-expand-actions {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
    }

    .act-btn-primary {
        flex: 1;
        background: #238B71;
        color: #fff;
        border: none;
        padding: 0.6rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
    }

    .act-btn-primary:hover {
        background: #238B71;
    }

    .act-btn-cancel {
        background: #fff;
        color: #475569;
        border: 1px solid #e2e8f0;
        padding: 0.6rem 1.25rem;
        border-radius: 0.5rem;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
        transition: 0.2s;
    }

    .act-btn-cancel:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }

    .act-resolve-alert {
        background: #fef9c3;
        border: 1px solid #fde68a;
        color: #92400e;
        padding: 0.55rem 0.85rem;
        border-radius: 0.4rem;
        font-size: 0.8rem;
        margin-bottom: 0.85rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .act-resolve-alert i {
        color: #d97706;
    }

    /* Reopen card (resolved state) */
    .act-card-reopen {
        border-color: #14b8a6;
    }
</style>


<!-- ── Hero ── -->
<div class="cc-hero">
    <div class="cc-hero-left">
        <a href="<?= base_url('admin/tickets') ?>" class="td-back-btn"><i class="bi bi-arrow-left"></i></a>
        <h2 class="cc-hero-title">Reporte #<?= esc(substr($ticket['hash'] ?? (string) $ticket['id'], -6)) ?>
        </h2>
        <div class="cc-hero-divider"></div>
        <div class="cc-hero-breadcrumb">
            <i class="bi bi-exclamation-circle"></i>
            <i class="bi bi-chevron-right"></i>
            <?= esc($ticket['subject'] ?? 'Sin Asunto') ?>
        </div>
    </div>

</div>
<!-- ── END Hero ── -->




<div class="row">
    <!-- LEFT SIDEBAR -->
    <div class="col-lg-3">
        <form id="td-action-form">
            <div class="td-sidebar">
                <h4>Detalles del Reporte</h4>

                <div class="td-meta-label"><i class="bi bi-clock-history"></i> Estado</div>
                <div class="td-meta-value"><span
                        class="td-badge-progreso"><?= esc($ticket['status_label'] ?? 'En Progreso') ?></span></div>

                <div class="td-meta-label"><i class="bi bi-exclamation-triangle"></i> Prioridad</div>
                <div class="td-meta-value">
                    <span class="badge"
                        style="background: <?= ($ticket['priority_value'] ?? '') === 'urgent' || ($ticket['priority_value'] ?? '') === 'critical' ? '#ef4444' : (($ticket['priority_value'] ?? '') === 'high' ? '#f97316' : '#eab308') ?>">
                        <?= esc($ticket['priority'] ?? 'Crítico') ?>
                    </span>
                </div>

                <div class="td-meta-label"><i class="bi bi-folder2-open"></i> Categoría</div>
                <div class="mb-3 dropdown w-100">
                    <button
                        class="btn btn-light btn-sm w-100 d-flex justify-content-between align-items-center text-start border bg-white"
                        type="button" data-bs-toggle="dropdown" aria-expanded="false" id="td-category-btn">
                        <span id="td-category-text" class="d-flex align-items-center gap-2 text-dark">
                            <i class="bi bi-tag text-secondary" id="td-category-icon"></i> Cargando...
                        </span>
                        <i class="bi bi-chevron-down text-secondary" style="font-size: 0.75rem;"></i>
                    </button>
                    <ul class="dropdown-menu w-100 shadow-sm border-1 border-light" id="td-category-list">
                        <li><a class="dropdown-item category-item d-flex align-items-center gap-2 py-2" href="#"
                                data-val="Mantenimiento" data-icon="bi-wrench-adjustable"><i
                                    class="bi bi-wrench-adjustable text-secondary w-15px text-center"></i>
                                Mantenimiento</a></li>
                        <li><a class="dropdown-item category-item d-flex align-items-center gap-2 py-2" href="#"
                                data-val="Amenidades" data-icon="bi-tools"><i
                                    class="bi bi-tools text-secondary w-15px text-center"></i> Amenidades</a></li>
                        <li><a class="dropdown-item category-item d-flex align-items-center gap-2 py-2" href="#"
                                data-val="Seguridad" data-icon="bi-shield-check"><i
                                    class="bi bi-shield-check text-secondary w-15px text-center"></i> Seguridad</a></li>
                        <li><a class="dropdown-item category-item d-flex align-items-center gap-2 py-2" href="#"
                                data-val="Mascotas" data-icon="bi-balloon-heart"><i
                                    class="bi bi-balloon-heart text-secondary w-15px text-center"></i> Mascotas</a></li>
                        <li><a class="dropdown-item category-item d-flex align-items-center gap-2 py-2" href="#"
                                data-val="Ruido" data-icon="bi-volume-up"><i
                                    class="bi bi-volume-up text-secondary w-15px text-center"></i> Ruido</a></li>
                        <li><a class="dropdown-item category-item d-flex align-items-center gap-2 py-2" href="#"
                                data-val="Vecinos" data-icon="bi-people"><i
                                    class="bi bi-people text-secondary w-15px text-center"></i> Vecinos</a></li>
                        <li><a class="dropdown-item category-item d-flex align-items-center gap-2 py-2" href="#"
                                data-val="Servicios" data-icon="bi-gear"><i
                                    class="bi bi-gear text-secondary w-15px text-center"></i> Servicios</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item category-item d-flex align-items-center gap-2 py-2" href="#"
                                data-val="Otro" data-icon="bi-three-dots"><i
                                    class="bi bi-three-dots text-secondary w-15px text-center"></i> Otro</a></li>
                    </ul>
                    <input type="hidden" id="td-category" name="category"
                        value="<?= esc($ticket['category'] ?: 'Otro') ?>">
                </div>

                <div class="td-meta-label"><i class="bi bi-person"></i> Reportado por</div>
                <div class="td-meta-value"><?= esc($ticket['reporter'] ?? 'Desconocido') ?></div>

                <div class="td-meta-label"><i class="bi bi-calendar3"></i> Creado</div>
                <div class="td-meta-value"><?= esc($ticket['created_at'] ?? 'Hoy') ?></div>

                <div class="td-meta-label"><i class="bi bi-person-badge"></i> Asignado a</div>
                <div class="mb-3 dropdown w-100">
                    <button
                        class="btn btn-light btn-sm w-100 d-flex justify-content-between align-items-center text-start border bg-white"
                        type="button" data-bs-toggle="dropdown" aria-expanded="false" id="td-assigned-btn">
                        <span id="td-assigned-text" class="d-flex align-items-center gap-2 text-dark"><span
                                class="spinner-border spinner-border-sm text-secondary" role="status"></span>
                            Cargando...</span>
                        <i class="bi bi-chevron-down text-secondary" style="font-size: 0.75rem;"></i>
                    </button>
                    <ul class="dropdown-menu w-100 shadow-sm border-1 border-light p-1" id="td-assigned-list"
                        style="max-height: 250px; overflow-y: auto;">
                        <!-- Dynamic loaded -->
                    </ul>
                    <input type="hidden" id="td-assigned" name="assigned_to"
                        value="<?= esc($ticket['assigned_to_type'] ? $ticket['assigned_to_type'] . '_' . $ticket['assigned_to_id'] : '') ?>">
                </div>

                <div class="td-meta-label"><i class="bi bi-hourglass-split"></i> Fecha Límite</div>
                <div class="td-meta-value text-secondary fst-italic">
                    <?= esc($ticket['due_date'] ?? 'Sin fecha límite') ?>
                </div>

                <div class="td-meta-label"><i class="bi bi-geo-alt"></i> Ubicación</div>
                <div class="td-meta-value text-secondary fst-italic">
                    <?= esc($ticket['location'] ?? 'Sin ubicación especificada') ?>
                </div>

                <div class="td-meta-label"><i class="bi bi-tags"></i> Etiquetas</div>
                <div class="td-meta-value text-secondary fst-italic"><?= esc($ticket['tags'] ?? 'Sin etiquetas') ?>
                </div>
            </div>
        </form>
    </div>

    <!-- MAIN CONTENT -->
    <div class="col-lg-9">
        <?php if ($ticket['status'] === 'open'): ?>
            <!-- Start Work Banner (visible only on Nuevo/open status) -->
            <div class="start-work-banner" id="start-work-banner">
                <button class="start-work-btn-main" id="start-work-btn">
                    <i class="bi bi-play-circle"></i>
                    Comenzar Trabajo
                    <span class="badge" id="start-work-badge">0</span>
                </button>
                <div class="start-work-hint">
                    <i class="bi bi-info-circle"></i>
                    Esto notificará al residente que está revisando su reporte y cambiará el estado a &ldquo;En
                    Progreso&rdquo;.
                </div>
            </div>
        <?php endif; ?>

        <ul class="nav nav-tabs td-nav-tabs" id="ticketDetailTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="tab-reporte" data-bs-toggle="tab" data-bs-target="#pane-reporte"
                    type="button" role="tab" aria-controls="pane-reporte" aria-selected="true"><i
                        class="bi bi-file-text"></i> Reporte</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-conversacion" data-bs-toggle="tab" data-bs-target="#pane-conversacion"
                    type="button" role="tab" aria-controls="pane-conversacion" aria-selected="false"><i
                        class="bi bi-chat-square-text"></i> Conversación</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-archivos" data-bs-toggle="tab" data-bs-target="#pane-archivos"
                    type="button" role="tab" aria-controls="pane-archivos" aria-selected="false"><i
                        class="bi bi-paperclip"></i> Archivos</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-acciones" data-bs-toggle="tab" data-bs-target="#pane-acciones"
                    type="button" role="tab" aria-controls="pane-acciones" aria-selected="false"><i
                        class="bi bi-gear"></i> Acciones</button>
            </li>
        </ul>

        <div class="tab-content" id="ticketDetailTabContent">
            <!-- Reporte Tab Pane -->
            <div class="tab-pane fade show active" id="pane-reporte" role="tabpanel" aria-labelledby="tab-reporte">
                <div class="td-panel">
                    <div class="td-panel-header">
                        <h3 class="td-panel-title"><i class="bi bi-tag text-primary"></i> Reporte</h3>
                        <div class="td-panel-badge"><i class="bi bi-clock me-1"></i> Hoy a las <?= date('h:i A') ?>
                        </div>
                    </div>

                    <div class="td-grid-box td-block-grid">
                        <div class="d-flex align-items-center gap-2">
                            <div class="td-avatar-circle"><?= substr((string) ($ticket['reporter'] ?? 'U'), 0, 2) ?>
                            </div>
                            <div>
                                <div class="td-meta-label m-0" style="font-size: 0.65rem;">REPORTADO POR</div>
                                <div class="fw-semibold text-dark" style="font-size: 0.85rem;">
                                    <?= esc($ticket['reporter'] ?? 'Desconocido') ?>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="td-meta-label m-0" style="font-size: 0.65rem;">UNIDAD</div>
                            <div class="text-secondary fst-italic" style="font-size: 0.85rem;">
                                <?= esc($ticket['unit_name'] ?? 'Sin unidad') ?>
                            </div>
                        </div>
                        <div>
                            <div class="td-meta-label m-0" style="font-size: 0.65rem;">CATEGORÍA</div>
                            <div class="fw-semibold" style="font-size: 0.85rem;">
                                <?= esc($ticket['category'] ?? 'Mantenimiento') ?>
                            </div>
                        </div>
                    </div>

                    <div class="td-grid-box">
                        <div class="td-meta-label mb-2" style="font-size: 0.65rem;">DESCRIPCIÓN</div>
                        <div class="text-dark" style="font-size: 0.9rem; white-space: pre-wrap;">
                            <?= esc($ticket['description'] ?? 'Sin descripción') ?>
                        </div>
                    </div>

                    <div class="td-grid-box mb-0">
                        <div class="td-meta-label mb-2" style="font-size: 0.65rem;">ADJUNTOS</div>
                        <?php if (!empty($ticket['media_urls'])):
                            $media = is_array($ticket['media_urls']) ? $ticket['media_urls'] : (json_decode($ticket['media_urls'], true) ?? []);
                            ?>
                            <div class="d-flex gap-2">
                                <?php foreach ($media as $mUrl):
                                    $secureUrl = base_url('admin/tickets/media/' . urlencode(basename($mUrl)));
                                    $isVid = preg_match('/\.(mp4|mov|webm)$/i', $mUrl);
                                    $isPdf = preg_match('/\.pdf$/i', $mUrl);
                                    $isDoc = preg_match('/\.(doc|docx|xls|xlsx|ppt|pptx|txt)$/i', $mUrl);
                                    ?>
                                    <?php if ($isVid): ?>
                                        <div class="position-relative report-attached-vid" data-url="<?= esc($secureUrl) ?>" style="width: 80px; height: 80px; cursor:pointer;">
                                            <video src="<?= esc($secureUrl) ?>#t=0.1" style="width: 100%; height: 100%; object-fit:cover; border-radius:8px; border:1px solid #e2e8f0; pointer-events: none;"></video>
                                            <div class="position-absolute top-50 start-50 translate-middle text-white d-flex align-items-center justify-content-center" style="background: rgba(0,0,0,0.5); border-radius: 50%; width: 30px; height: 30px;">
                                                <i class="bi bi-play-fill"></i>
                                            </div>
                                        </div>
                                    <?php elseif ($isPdf || $isDoc): ?>
                                        <a href="<?= esc($secureUrl) ?>" target="_blank" class="d-flex align-items-center justify-content-center text-decoration-none" style="width: 80px; height: 80px; border-radius:8px; border:1px solid #e2e8f0; background: #f8fafc; color: #475569; font-size: 2rem; transition: 0.2s;" title="Abrir Documento">
                                            <i class="bi <?= $isPdf ? 'bi-file-earmark-pdf text-danger' : 'bi-file-earmark-text text-primary' ?>"></i>
                                        </a>
                                    <?php else: ?>
                                        <img src="<?= esc($secureUrl) ?>" class="report-attached-img"
                                            style="width: 80px; height: 80px; object-fit:cover; border-radius:8px; border:1px solid #e2e8f0; cursor:pointer;">
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-secondary fst-italic" style="font-size: 0.85rem;">Sin adjuntos</div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="td-panel">
                    <h3 class="td-panel-title mb-4"><i class="bi bi-activity text-secondary"></i> Actividad Reciente
                    </h3>
                    <div class="td-timeline">
                        <div class="td-timeline-item">
                            <p class="td-timeline-title">Reporte creado</p>
                            <span class="td-timeline-date"><?= esc($ticket['created_at'] ?? 'Hoy') ?></span>
                        </div>
                        <div class="td-timeline-item">
                            <p class="td-timeline-title">Asignado a
                                <?= esc($ticket['assigned_to_name'] ?? 'Administrador') ?>
                            </p>
                            <span class="td-timeline-date"><?= esc($ticket['created_at'] ?? 'Hoy') ?></span>
                        </div>
                        <div class="td-timeline-item">
                            <p class="td-timeline-title">Reasignado a <?= esc($ticket['reporter'] ?? 'Desconocido') ?>
                            </p>
                            <span class="td-timeline-date"><?= esc($ticket['created_at'] ?? 'Hoy') ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Conversación Tab Pane -->
            <div class="tab-pane fade" id="pane-conversacion" role="tabpanel" aria-labelledby="tab-conversacion">
                <!-- Sub-tabs: Responder al residente | Nota interna -->
                <div class="conv-subtabs mb-0">
                    <button class="conv-subtab active" id="subtab-reply" data-type="reply">
                        <i class="bi bi-globe2"></i> Responder al residente
                    </button>
                    <button class="conv-subtab" id="subtab-internal" data-type="internal">
                        <i class="bi bi-lock"></i> Nota interna
                    </button>
                </div>

                <!-- Context hint -->
                <div class="conv-context-hint" id="conv-context-hint">
                    <i class="bi bi-info-circle"></i>
                    <span id="conv-context-text">Este mensaje se enviará al residente y será visible en su
                        aplicación</span>
                </div>

                <!-- Chat thread area -->
                <div class="conv-thread" id="conv-thread">
                    <div class="text-center py-5" id="conv-empty">
                        <i class="bi bi-chat-dots text-secondary" style="font-size: 3rem;"></i>
                        <p class="text-secondary mt-3 mb-0">Aún no hay mensajes en esta conversación.</p>
                        <p class="text-secondary small">Los comentarios y actualizaciones aparecerán aquí.</p>
                    </div>
                    <!-- Messages rendered here by JS -->
                </div>

                <!-- Quick responses (only visible on reply mode) -->
                <div class="conv-quick-wrap" id="conv-quick-wrap">
                    <div class="conv-quick-label"><i class="bi bi-stars"></i> Respuestas Rápidas</div>
                    <div class="conv-quick-pills">
                        <button class="conv-pill"
                            data-text="Estimado residente, estamos investigando su problema y le mantendremos informado.">Investigando</button>
                        <button class="conv-pill"
                            data-text="El trabajo en su reporte ha comenzado. Le mantendremos informado.">Trabajo
                            iniciado</button>
                        <button class="conv-pill"
                            data-text="Necesitamos información adicional de su parte para proceder. ¿Podría proporcionarnos más detalles?">Se
                            necesita más información</button>
                        <button class="conv-pill"
                            data-text="El problema ha sido resuelto. Por favor háganos saber si necesita algo más.">Problema
                            resuelto</button>
                    </div>
                </div>

                <!-- Message input -->
                <div class="conv-input-wrap" id="conv-input-wrap">
                    <div class="conv-input-row">
                        <label class="conv-attach-btn" for="conv-file-input" title="Adjuntar archivo">
                            <i class="bi bi-paperclip"></i>
                        </label>
                        <input type="file" id="conv-file-input" multiple accept="image/*,video/*,.pdf,.doc,.docx,.xls,.xlsx" style="display:none;">
                        <div class="conv-input-field-wrap">
                            <textarea class="conv-textarea" id="conv-message" placeholder="Escribe un mensaje..."
                                rows="1" maxlength="1000"></textarea>
                            <span class="conv-char-count" id="conv-char-count">1000</span>
                        </div>
                        <button class="conv-send-btn" id="conv-send-btn" title="Enviar">
                            <i class="bi bi-send-fill"></i>
                        </button>
                    </div>
                    <div class="conv-file-preview d-none" id="conv-file-preview"></div>
                    <div class="conv-hint" id="conv-hint">Presiona Ctrl+Enter para enviar</div>
                </div>
            </div>

            <!-- Archivos Tab Pane -->
            <div class="tab-pane fade" id="pane-archivos" role="tabpanel" aria-labelledby="tab-archivos">
                <!-- Header bar -->
                <div class="files-header">
                    <div class="files-header-left">
                        <span class="fw-bold text-dark" style="font-size:0.95rem;">Todos los Archivos</span>
                        <span class="badge bg-secondary rounded-pill ms-1" id="files-total-badge">0</span>
                    </div>
                    <div class="files-header-right">
                        <label class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1"
                            for="files-upload-input" style="cursor:pointer;">
                            <i class="bi bi-cloud-arrow-up"></i> Subir
                        </label>
                        <input type="file" id="files-upload-input" multiple accept="image/*,video/*,.pdf,.doc,.docx,.xls,.xlsx"
                            style="display:none;">
                        <div class="btn-group btn-group-sm ms-2" role="group">
                            <button class="btn btn-outline-secondary active" id="files-view-grid"
                                title="Vista cuadrícula"><i class="bi bi-grid-3x3-gap-fill"></i></button>
                            <button class="btn btn-outline-secondary" id="files-view-list" title="Vista lista"><i
                                    class="bi bi-list-ul"></i></button>
                        </div>
                    </div>
                </div>
                <!-- File cards container -->
                <div class="files-grid" id="files-grid">
                    <div class="text-center py-5" id="files-empty">
                        <i class="bi bi-folder2-open text-secondary" style="font-size: 3rem;"></i>
                        <p class="text-secondary mt-3 mb-0">No hay archivos adjuntos en este ticket.</p>
                    </div>
                </div>
            </div>

            <!-- Acciones Tab Pane -->
            <div class="tab-pane fade" id="pane-acciones" role="tabpanel" aria-labelledby="tab-acciones">
                <!-- Header -->
                <div class="act-header">
                    <h3 class="act-title">Acciones</h3>
                    <?php
                    $statusBadgeMap = [
                        'open' => ['bg' => '#238B71', 'label' => 'Nuevo'],
                        'in_progress' => ['bg' => '#238B71', 'label' => 'En Progreso'],
                        'resolved' => ['bg' => '#14b8a6', 'label' => 'Resuelto'],
                        'closed' => ['bg' => '#64748b', 'label' => 'Cerrado'],
                    ];
                    $sb = $statusBadgeMap[$ticket['status']] ?? ['bg' => '#94a3b8', 'label' => ucfirst($ticket['status'])];
                    ?>
                    <span class="act-status-badge" style="background: <?= $sb['bg'] ?>;"><?= $sb['label'] ?></span>
                </div>

                <div class="act-body" id="act-body">
                    <?php if ($ticket['status'] === 'open'): ?>
                        <!-- Comenzar trabajo (open only) -->
                        <div class="act-card" id="act-card-start">
                            <div class="act-card-row" data-action="start">
                                <div class="act-card-icon"><i class="bi bi-play-circle"></i></div>
                                <div class="act-card-text">
                                    <div class="act-card-label">Comenzar Trabajo</div>
                                    <div class="act-card-sub">Iniciar progreso en este reporte</div>
                                </div>
                                <i class="bi bi-chevron-right act-card-arrow"></i>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (in_array($ticket['status'], ['open', 'in_progress'])): ?>
                        <!-- Necesito más info -->
                        <div class="act-card" id="act-card-info">
                            <div class="act-card-row" data-action="info">
                                <div class="act-card-icon"><i class="bi bi-chat-left-quote"></i></div>
                                <div class="act-card-text">
                                    <div class="act-card-label">Necesito Más Info</div>
                                    <div class="act-card-sub">Solicitar información adicional al residente</div>
                                </div>
                                <i class="bi bi-chevron-right act-card-arrow"></i>
                            </div>
                            <!-- Expanded form (hidden initially) -->
                            <div class="act-expand" id="act-expand-info" style="display:none;">
                                <div class="act-expand-inner">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label class="act-field-label text-primary">Mensaje</label>
                                        <span class="act-char-counter" id="act-info-counter">902 caracteres restantes</span>
                                    </div>
                                    <textarea class="act-textarea" id="act-info-msg" maxlength="1000"
                                        rows="3">Necesitamos información adicional de su parte para proceder. ¿Podría proporcionarnos más detalles?</textarea>
                                    <div class="act-expand-hint"><i class="bi bi-eye"></i> Este mensaje será visible para el
                                        residente.</div>
                                    <div class="act-expand-actions">
                                        <button class="act-btn-primary" id="act-info-submit"><i
                                                class="bi bi-chat-left-quote"></i> Necesito Más Info</button>
                                        <button class="act-btn-cancel" id="act-info-cancel">Cancelar</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Marcar Resuelto -->
                        <div class="act-card act-card-featured" id="act-card-resolve">
                            <div class="act-card-row" data-action="resolve">
                                <div class="act-card-icon act-icon-primary"><i class="bi bi-check-circle"></i></div>
                                <div class="act-card-text">
                                    <div class="act-card-label act-label-white">Marcar Resuelto</div>
                                    <div class="act-card-sub act-sub-white">Cerrar este reporte como resuelto</div>
                                </div>
                                <i class="bi bi-chevron-right act-card-arrow act-arrow-white"></i>
                            </div>
                            <div class="act-expand" id="act-expand-resolve" style="display:none;">
                                <div class="act-expand-inner">
                                    <div class="act-resolve-alert">
                                        <i class="bi bi-info-circle"></i> Este cambio de estado requiere una nota de
                                        resolución.
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label class="act-field-label">Mensaje <span class="text-danger">*</span></label>
                                        <span class="act-char-counter" id="act-resolve-counter">913 caracteres
                                            restantes</span>
                                    </div>
                                    <textarea class="act-textarea" id="act-resolve-msg" maxlength="1000"
                                        rows="4">Su reporte ha sido resuelto. Si tiene alguna otra inquietud, por favor háganoslo saber.</textarea>
                                    <div class="act-expand-hint"><i class="bi bi-eye"></i> Este mensaje será visible para el
                                        residente.</div>
                                    <div class="act-expand-actions">
                                        <button class="act-btn-primary" id="act-resolve-submit"><i
                                                class="bi bi-check-circle"></i> Marcar Resuelto</button>
                                        <button class="act-btn-cancel" id="act-resolve-cancel">Cancelar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($ticket['status'] === 'resolved'): ?>
                        <!-- Reabrir -->
                        <div class="act-card" id="act-card-reopen">
                            <div class="act-card-row" data-action="reopen">
                                <div class="act-card-icon"><i class="bi bi-arrow-counterclockwise"></i></div>
                                <div class="act-card-text">
                                    <div class="act-card-label">Reabrir</div>
                                    <div class="act-card-sub">Reabrir este reporte para más trabajo</div>
                                </div>
                                <i class="bi bi-chevron-right act-card-arrow"></i>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image and Video Lightbox Overlay -->
<div class="lightbox-overlay" id="lightbox-overlay">
    <button class="lightbox-close" id="lightbox-close"><i class="bi bi-x-lg"></i></button>
    <div class="lightbox-content" id="lightbox-content">
        <img src="" alt="" id="lightbox-img">
        <video src="" id="lightbox-vid" controls style="display:none; max-width: 90vw; max-height: 80vh; border-radius: 12px; box-shadow: 0 8px 40px rgba(0, 0, 0, 0.6);"></video>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Dropdown Auto-save functions
        const ticketId = '<?= esc($ticket["id"]) ?>';

        function autoSaveField(key, val, textElemId, loadingHtml, onSuccessHtml) {
            const fd = new FormData();
            fd.append(key, val);
            fd.append('status', '<?= esc($ticket['status']) ?>');

            const txt = document.getElementById(textElemId);
            const oldHtml = txt.innerHTML;
            txt.innerHTML = loadingHtml;

            fetch('<?= base_url("admin/tickets/update-details/") ?>' + ticketId, {
                method: 'POST', body: fd
            }).then(r => r.json()).then(res => {
                if (res.status === 200) {
                    txt.innerHTML = onSuccessHtml;
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            toast: true, position: 'top-end', icon: 'success',
                            title: 'Actualizado automáticamente', showConfirmButton: false, timer: 3000
                        });
                    }
                } else {
                    txt.innerHTML = oldHtml;
                    if (typeof Swal !== 'undefined') Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'Error: ' + res.error, showConfirmButton: false, timer: 3000 });
                }
            }).catch(() => {
                txt.innerHTML = oldHtml;
                if (typeof Swal !== 'undefined') Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'Error de conexión', showConfirmButton: false, timer: 3000 });
            });
        }

        // Category Logic
        const catInput = document.getElementById('td-category');
        const catItems = document.querySelectorAll('.category-item');
        const setCategoryUI = (val) => {
            const item = Array.from(catItems).find(i => i.dataset.val === val) || Array.from(catItems).find(i => i.dataset.val === 'Otro');
            const icon = item.dataset.icon;
            catItems.forEach(i => i.innerHTML = i.innerHTML.replace(' <i class="bi bi-check ms-auto"></i>', ''));
            item.innerHTML += ' <i class="bi bi-check ms-auto"></i>';
            return `<i class="bi ${icon} text-secondary"></i> ${val}`;
        };
        document.getElementById('td-category-text').innerHTML = setCategoryUI(catInput.value);

        catItems.forEach(i => {
            i.addEventListener('click', (e) => {
                e.preventDefault();
                const val = i.dataset.val;
                if (catInput.value === val) return;
                catInput.value = val;
                const newHtml = setCategoryUI(val);
                autoSaveField('category', val, 'td-category-text', '<span class="spinner-border spinner-border-sm text-secondary" role="status"></span> Guardando...', newHtml);
            });
        });

        // Assignee Logic
        const assignedInput = document.getElementById('td-assigned');
        const assignedTxt = document.getElementById('td-assigned-text');

        const renderAssigneeUI = (val, name, initials) => {
            if (!val) {
                return `<div class="td-avatar-circle bg-light border border-dashed text-muted" style="width:20px;height:20px;font-size:0.6rem;">-</div> <span class="text-secondary">No asignado</span>`;
            }
            return `<div class="td-avatar-circle bg-primary-subtle text-primary" style="width:20px;height:20px;font-size:0.6rem;">${initials}</div> <span>${name}</span>`;
        };

        fetch('<?= base_url("admin/tickets/assignees") ?>')
            .then(r => r.json())
            .then(res => {
                const list = document.getElementById('td-assigned-list');
                const currVal = assignedInput.value;
                let activeName = 'No asignado', activeInitials = '-';

                list.innerHTML = `<li><a class="dropdown-item assigned-item d-flex align-items-center gap-2 py-2 rounded" href="#" data-val="" data-name="No asignado" data-initials="-"><div class="td-avatar-circle bg-light border border-dashed text-muted" style="width:20px;height:20px;font-size:0.6rem;">-</div> <span class="text-secondary">No asignado</span></a></li>`;

                if (res.admins && res.admins.length > 0) {
                    list.innerHTML += `<li><h6 class="dropdown-header fw-bold bg-light text-dark mx-1 mt-2 mb-1 rounded" style="font-size:0.75rem;">Administradores</h6></li>`;
                    res.admins.forEach(ad => {
                        let name = (ad.first_name || '') + ' ' + (ad.last_name || '');
                        name = name.trim() || 'Admin';
                        let initials = name.substring(0, 2).toUpperCase() || 'U';
                        let val = 'user_' + ad.id;
                        if (currVal === val) { activeName = name; activeInitials = initials; }
                        list.innerHTML += `<li><a class="dropdown-item assigned-item d-flex align-items-center gap-2 py-2 rounded" href="#" data-val="${val}" data-name="${name}" data-initials="${initials}"><div class="td-avatar-circle bg-primary-subtle text-primary" style="width:20px;height:20px;font-size:0.6rem;">${initials}</div> <span>${name}</span></a></li>`;
                    });
                }

                if (res.staff && res.staff.length > 0) {
                    list.innerHTML += `<li><h6 class="dropdown-header fw-bold bg-light text-dark mx-1 mt-2 mb-1 rounded" style="font-size:0.75rem;">Personal / Staff</h6></li>`;
                    res.staff.forEach(st => {
                        let name = (st.first_name || '') + ' ' + (st.last_name || '');
                        name = name.trim() || 'Staff';
                        let initials = name.substring(0, 2).toUpperCase() || 'S';
                        let val = 'staff_' + st.id;
                        if (currVal === val) { activeName = name; activeInitials = initials; }
                        list.innerHTML += `<li><a class="dropdown-item assigned-item d-flex align-items-center gap-2 py-2 rounded" href="#" data-val="${val}" data-name="${name}" data-initials="${initials}"><div class="td-avatar-circle bg-success-subtle text-success" style="width:20px;height:20px;font-size:0.6rem;">${initials}</div> <span>${name}</span></a></li>`;
                    });
                }

                // Initial Rendering
                assignedTxt.innerHTML = renderAssigneeUI(currVal, activeName, activeInitials);

                // Add Checkmarks
                const updateCheckmarks = (v) => {
                    document.querySelectorAll('.assigned-item').forEach(i => {
                        i.innerHTML = i.innerHTML.replace(' <i class="bi bi-check ms-auto"></i>', '');
                        if (i.dataset.val === v) i.innerHTML += ' <i class="bi bi-check ms-auto"></i>';
                    });
                };
                updateCheckmarks(currVal);

                // Attach click events
                document.querySelectorAll('.assigned-item').forEach(el => {
                    el.addEventListener('click', (e) => {
                        e.preventDefault();
                        const val = el.dataset.val;
                        if (assignedInput.value === val) return;

                        assignedInput.value = val;
                        const name = el.dataset.name;
                        const init = el.dataset.initials;
                        updateCheckmarks(val);

                        const newHtml = renderAssigneeUI(val, name, init);
                        autoSaveField('assigned_to', val, 'td-assigned-text', '<span class="spinner-border spinner-border-sm text-secondary" role="status"></span> Guardando...', newHtml);
                    });
                });
            });

        // Action Buttons Logic
        function sendStatusUpdate(newStatus, confirmMsg) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: confirmMsg,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, continuar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        doStatusUpdate(newStatus);
                    }
                });
            } else {
                if (confirm(confirmMsg)) doStatusUpdate(newStatus);
            }
        }

        function doStatusUpdate(newStatus) {
            const fd = new FormData();
            fd.append('status', newStatus);
            fetch('<?= base_url("admin/tickets/update-details/") ?>' + ticketId, {
                method: 'POST', body: fd
            }).then(r => r.json()).then(res => {
                if (res.status === 200) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Estado actualizado', showConfirmButton: false, timer: 2000 });
                    }
                    setTimeout(() => location.reload(), 1500);
                } else {
                    if (typeof Swal !== 'undefined') Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'Error: ' + (res.error || ''), showConfirmButton: false, timer: 3000 });
                }
            }).catch(() => {
                if (typeof Swal !== 'undefined') Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'Error de conexión', showConfirmButton: false, timer: 3000 });
            });
        }

        // ═══════════════════════════════════════════════════
        // ▌ ACCIONES MODULE
        // ═══════════════════════════════════════════════════

        // Helper: toggle expand panel
        function toggleExpand(panelId, show) {
            const panel = document.getElementById(panelId);
            if (panel) {
                panel.style.display = show ? 'block' : 'none';
            }
        }

        // Helper: char counter update
        function setupCharCounter(textareaId, counterId) {
            const ta = document.getElementById(textareaId);
            const counter = document.getElementById(counterId);
            if (!ta || !counter) return;
            const update = () => {
                const remaining = 1000 - ta.value.length;
                counter.textContent = `${remaining} caracteres restantes`;
            };
            ta.addEventListener('input', update);
            update();
        }

        // Setup char counters
        setupCharCounter('act-info-msg', 'act-info-counter');
        setupCharCounter('act-resolve-msg', 'act-resolve-counter');

        // Card row clicks → expand forms or direct action
        document.querySelectorAll('.act-card-row').forEach(row => {
            row.addEventListener('click', () => {
                const action = row.dataset.action;

                if (action === 'info') {
                    // Hide resolve, show info
                    toggleExpand('act-expand-resolve', false);
                    toggleExpand('act-expand-info', true);
                    // Also hide the row styling for featured card
                    const card = document.getElementById('act-card-resolve');
                    if (card) card.classList.remove('act-card-featured');
                } else if (action === 'resolve') {
                    toggleExpand('act-expand-info', false);
                    toggleExpand('act-expand-resolve', true);
                    // Remove the featured class when expanded for clarity
                    const card = document.getElementById('act-card-resolve');
                    if (card) card.classList.remove('act-card-featured');
                } else if (action === 'start') {
                    // Start work → direct confirmation
                    sendStatusUpdate('in_progress', 'El ticket se marcará como "En progreso" y se notificará al residente');
                } else if (action === 'reopen') {
                    // Reopen → direct confirmation
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Confirmar Cambio de Estado',
                            text: '¿Está seguro que desea reabrir este reporte?',
                            showCancelButton: true,
                            confirmButtonColor: '#475569',
                            confirmButtonText: 'Confirmar',
                            cancelButtonText: 'Cancelar',
                            customClass: { popup: 'act-swal-popup' }
                        }).then(r => { if (r.isConfirmed) doStatusUpdate('in_progress'); });
                    } else {
                        if (confirm('¿Reabrir este reporte?')) doStatusUpdate('in_progress');
                    }
                }
            });
        });

        // Cancel buttons
        const infoCancel = document.getElementById('act-info-cancel');
        if (infoCancel) infoCancel.addEventListener('click', () => {
            toggleExpand('act-expand-info', false);
        });
        const resolveCancel = document.getElementById('act-resolve-cancel');
        if (resolveCancel) resolveCancel.addEventListener('click', () => {
            toggleExpand('act-expand-resolve', false);
            // Restore featured style
            const card = document.getElementById('act-card-resolve');
            if (card) card.classList.add('act-card-featured');
        });

        // Necesito Más Info Submit → send message + toast
        const infoSubmit = document.getElementById('act-info-submit');
        if (infoSubmit) infoSubmit.addEventListener('click', async () => {
            const msg = document.getElementById('act-info-msg').value.trim();
            if (!msg) return;

            infoSubmit.disabled = true;
            infoSubmit.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Enviando...';

            try {
                const fd = new FormData();
                fd.append('message', msg);
                fd.append('type', 'reply');
                const res = await fetch(CONV_API, { method: 'POST', body: fd });
                const data = await res.json();
                if (data.status === 201) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Solicitud de información enviada', showConfirmButton: false, timer: 2500 });
                    }
                    toggleExpand('act-expand-info', false);
                    loadMessages();
                }
            } catch (e) {
                if (typeof Swal !== 'undefined') Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'Error al enviar', showConfirmButton: false, timer: 3000 });
            }
            infoSubmit.disabled = false;
            infoSubmit.innerHTML = '<i class="bi bi-chat-left-quote"></i> Necesito Más Info';
        });

        // Marcar Resuelto Submit → confirmation modal → send message + change status
        const resolveSubmit = document.getElementById('act-resolve-submit');
        if (resolveSubmit) resolveSubmit.addEventListener('click', async () => {
            const msg = document.getElementById('act-resolve-msg').value.trim();
            if (!msg) {
                document.getElementById('act-resolve-msg').style.borderColor = '#ef4444';
                return;
            }
            document.getElementById('act-resolve-msg').style.borderColor = '';

            // Show premium confirmation modal
            if (typeof Swal !== 'undefined') {
                const result = await Swal.fire({
                    title: 'Confirmar Cambio de Estado',
                    text: '¿Está seguro que desea cambiar el estado de "En Progreso" a "Resuelto"?',
                    showCancelButton: true,
                    confirmButtonColor: '#475569',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar',
                    customClass: { popup: 'act-swal-popup' },
                    reverseButtons: true
                });

                if (!result.isConfirmed) return;
            } else {
                if (!confirm('¿Cambiar estado a Resuelto?')) return;
            }

            resolveSubmit.disabled = true;
            resolveSubmit.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Procesando...';

            try {
                // 1. Send resolution message to conversation
                const fd = new FormData();
                fd.append('message', msg);
                fd.append('type', 'reply');
                await fetch(CONV_API, { method: 'POST', body: fd });

                // 2. Change status to resolved
                doStatusUpdate('resolved');
            } catch (e) {
                if (typeof Swal !== 'undefined') Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'Error al procesar', showConfirmButton: false, timer: 3000 });
                resolveSubmit.disabled = false;
                resolveSubmit.innerHTML = '<i class="bi bi-check-circle"></i> Marcar Resuelto';
            }
        });

        // Start Work Banner (prominent version above tabs)
        const startWorkBtn = document.getElementById('start-work-btn');
        if (startWorkBtn) startWorkBtn.addEventListener('click', () => sendStatusUpdate('in_progress', 'El ticket se marcará como "En progreso" y se notificará al residente'));

        // ═══════════════════════════════════════════════════
        // ▌ CONVERSATION MODULE
        // ═══════════════════════════════════════════════════
        const CONV_API = '<?= base_url("admin/tickets/") ?>' + ticketId + '/messages';
        let convMode = 'reply';
        let convFiles = [];
        let allMessages = [];

        // DOM refs
        const convThread = document.getElementById('conv-thread');
        const convEmpty = document.getElementById('conv-empty');
        const convMsg = document.getElementById('conv-message');
        const convCharCount = document.getElementById('conv-char-count');
        const convSendBtn = document.getElementById('conv-send-btn');
        const convHint = document.getElementById('conv-hint');
        const convCtxHint = document.getElementById('conv-context-hint');
        const convCtxText = document.getElementById('conv-context-text');
        const convQuickWrap = document.getElementById('conv-quick-wrap');
        const convFieldWrap = document.querySelector('.conv-input-field-wrap');
        const convFileInput = document.getElementById('conv-file-input');
        const convFilePrev = document.getElementById('conv-file-preview');
        const convTabBtn = document.getElementById('tab-conversacion');

        // ── Sub-tab switching ──
        document.querySelectorAll('.conv-subtab').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.conv-subtab').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                convMode = btn.dataset.type;
                applyConvTheme();
                renderMessages();
            });
        });

        function applyConvTheme() {
            const isInternal = convMode === 'internal';
            // Context hint
            convCtxHint.className = 'conv-context-hint ' + (isInternal ? 'mode-internal' : 'mode-reply');
            convCtxText.textContent = isInternal
                ? 'Esta nota solo será visible para administradores y personal'
                : 'Este mensaje se enviará al residente y será visible en su aplicación';

            // Quick responses only on reply
            convQuickWrap.style.display = isInternal ? 'none' : '';

            // Send button
            convSendBtn.className = 'conv-send-btn ' + (isInternal ? 'mode-internal' : 'mode-reply');

            // Input border
            convFieldWrap.classList.toggle('mode-internal', isInternal);

            // Hint
            convHint.textContent = isInternal ? 'Presiona Ctrl+Enter para enviar  (Nota interna)' : 'Presiona Ctrl+Enter para enviar';
        }
        applyConvTheme();

        // ── Character counter + auto-resize ──
        convMsg.addEventListener('input', () => {
            const rem = 1000 - convMsg.value.length;
            convCharCount.textContent = rem;
            convMsg.style.height = 'auto';
            convMsg.style.height = Math.min(convMsg.scrollHeight, 100) + 'px';
        });

        // ── Ctrl+Enter to send ──
        convMsg.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && (e.ctrlKey || e.metaKey)) {
                e.preventDefault();
                sendConvMessage();
            }
        });

        // ── Quick response pills ──
        document.querySelectorAll('.conv-pill').forEach(pill => {
            pill.addEventListener('click', () => {
                convMsg.value = pill.dataset.text;
                convMsg.dispatchEvent(new Event('input'));
                convMsg.focus();
            });
        });

        // ── File attach ──
        convFileInput.addEventListener('change', () => {
            const newFiles = Array.from(convFileInput.files);
            convFiles = [...convFiles, ...newFiles].slice(0, 5);
            renderFilePreviews();
            convFileInput.value = '';
        });

        function renderFilePreviews() {
            if (convFiles.length === 0) {
                convFilePrev.classList.add('d-none');
                convFilePrev.innerHTML = '';
                return;
            }
            convFilePrev.classList.remove('d-none');
            convFilePrev.innerHTML = '';
            convFiles.forEach((f, i) => {
                const div = document.createElement('div');
                div.className = 'conv-file-thumb';
                
                const isPdf = f.name.toLowerCase().endsWith('.pdf');
                const isDoc = /\.(doc|docx|xls|xlsx|ppt|pptx|txt)$/i.test(f.name);
                
                if (isPdf || isDoc) {
                    div.innerHTML = `<div class="d-flex align-items-center justify-content-center w-100 h-100 bg-light ${isPdf ? 'text-danger' : 'text-primary'}" style="font-size: 2rem;"><i class="bi ${isPdf ? 'bi-file-earmark-pdf' : 'bi-file-earmark-text'}"></i></div>`;
                } else {
                    const img = document.createElement('img');
                    img.src = URL.createObjectURL(f);
                    div.appendChild(img);
                }

                const rm = document.createElement('button');
                rm.className = 'conv-file-remove';
                rm.innerHTML = '<i class="bi bi-x"></i>';
                rm.addEventListener('click', () => {
                    convFiles.splice(i, 1);
                    renderFilePreviews();
                });
                div.appendChild(rm);
                convFilePrev.appendChild(div);
            });
        }

        // ── Send message ──
        async function sendConvMessage() {
            const msg = convMsg.value.trim();
            if (!msg && convFiles.length === 0) return;

            convSendBtn.disabled = true;
            const fd = new FormData();
            fd.append('message', msg);
            fd.append('type', convMode);
            convFiles.forEach(f => fd.append('media[]', f));

            try {
                const res = await fetch(CONV_API, { method: 'POST', body: fd });
                const data = await res.json();
                if (data.status === 201) {
                    convMsg.value = '';
                    convMsg.style.height = 'auto';
                    convCharCount.textContent = '1000';
                    convFiles = [];
                    renderFilePreviews();
                    await loadMessages();
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: convMode === 'internal' ? 'Nota interna guardada' : 'Mensaje enviado', showConfirmButton: false, timer: 2000 });
                    }
                } else {
                    if (typeof Swal !== 'undefined') Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: data.error || 'Error al enviar', showConfirmButton: false, timer: 3000 });
                }
            } catch {
                if (typeof Swal !== 'undefined') Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'Error de conexión', showConfirmButton: false, timer: 3000 });
            }
            convSendBtn.disabled = false;
        }

        convSendBtn.addEventListener('click', sendConvMessage);

        // ── Load messages ──
        async function loadMessages() {
            try {
                const res = await fetch(CONV_API);
                const data = await res.json();
                if (data.status === 200) {
                    allMessages = data.messages;
                    // Update tab badge
                    const totalCount = data.count || 0;
                    if (totalCount > 0) {
                        convTabBtn.innerHTML = `<i class="bi bi-chat-square-text"></i> Conversación <span class="badge bg-primary rounded-pill ms-1" style="font-size:0.65rem;">${totalCount}</span>`;
                    }
                    // Update start-work banner badge
                    const swBadge = document.getElementById('start-work-badge');
                    if (swBadge) swBadge.textContent = totalCount;
                    renderMessages();
                }
            } catch (e) { console.error('Error loading messages:', e); }
        }

        // ── Render messages ──
        function renderMessages() {
            const filtered = allMessages.filter(m => m.type === convMode);

            // Remove old rendered messages (keep empty if present)
            convThread.querySelectorAll('.conv-msg, .conv-date-divider').forEach(el => el.remove());

            if (filtered.length === 0) {
                convEmpty.style.display = '';
                return;
            }
            convEmpty.style.display = 'none';

            let lastDate = '';
            filtered.forEach(m => {
                // Date divider
                if (m.date_label !== lastDate) {
                    lastDate = m.date_label;
                    const divider = document.createElement('div');
                    divider.className = 'conv-date-divider';
                    divider.innerHTML = `<span>${m.date_label}</span>`;
                    convThread.appendChild(divider);
                }

                const isInternal = m.type === 'internal';
                const avatarClass = isInternal ? 'internal-avatar' : 'reply-avatar';
                const bubbleClass = isInternal ? 'bubble-internal' : 'bubble-reply';

                const msgDiv = document.createElement('div');
                msgDiv.className = 'conv-msg';

                // Header
                let badgeHtml = `<span class="conv-msg-badge badge-admin">Administrador</span>`;
                if (isInternal) {
                    badgeHtml += ` <span class="conv-msg-badge badge-internal"><i class="bi bi-lock"></i> Nota interna</span>`;
                }

                let html = `
                    <div class="conv-msg-header">
                        <div class="conv-msg-avatar ${avatarClass}">${m.initials}</div>
                        <span class="conv-msg-name">${escHtml(m.name)}</span>
                        ${badgeHtml}
                    </div>`;

                // Bubble
                if (m.message) {
                    html += `<div class="conv-msg-bubble ${bubbleClass}">${escHtml(m.message)}</div>`;
                }

                // Media
                if (m.media_urls && m.media_urls.length > 0) {
                    html += `<div class="conv-msg-media">`;
                    m.media_urls.forEach(url => {
                        if (/\.(mp4|mov|webm)$/i.test(url)) {
                            html += `
                            <div class="position-relative conv-msg-vid" data-url="${escHtml(url)}" style="width: 200px; height: 140px; cursor:pointer;">
                                <video src="${escHtml(url)}#t=0.1" style="width: 100%; height: 100%; object-fit:cover; border-radius:12px; border:2px solid #e2e8f0; pointer-events: none;"></video>
                                <div class="position-absolute top-50 start-50 translate-middle text-white d-flex align-items-center justify-content-center" style="background: rgba(0,0,0,0.5); border-radius: 50%; width: 40px; height: 40px; transition: 0.2s;">
                                    <i class="bi bi-play-fill fs-3"></i>
                                </div>
                            </div>`;
                        } else if (/\.pdf$/i.test(url)) {
                            html += `
                            <a href="${escHtml(url)}" target="_blank" class="d-flex align-items-center justify-content-center text-decoration-none" style="width: 140px; height: 100px; border-radius:12px; border:2px solid #e2e8f0; background: #f8fafc; color: #475569; font-size: 2.5rem; transition: 0.2s; margin-top: 0.35rem;">
                                <i class="bi bi-file-earmark-pdf text-danger"></i>
                            </a>`;
                        } else if (/\.(doc|docx|xls|xlsx|ppt|pptx|txt)$/i.test(url)) {
                            html += `
                            <a href="${escHtml(url)}" target="_blank" class="d-flex align-items-center justify-content-center text-decoration-none" style="width: 140px; height: 100px; border-radius:12px; border:2px solid #e2e8f0; background: #f8fafc; color: #475569; font-size: 2.5rem; transition: 0.2s; margin-top: 0.35rem;">
                                <i class="bi bi-file-earmark-text text-primary"></i>
                            </a>`;
                        } else {
                            html += `<img src="${escHtml(url)}" style="cursor:pointer;">`;
                        }
                    });
                    html += `</div>`;
                }

                html += `<div class="conv-msg-time">${escHtml(m.time_label)}</div>`;
                msgDiv.innerHTML = html;
                convThread.appendChild(msgDiv);
            });

            // Auto-scroll to bottom
            convThread.scrollTop = convThread.scrollHeight;
        }

        function escHtml(s) {
            const d = document.createElement('div');
            d.textContent = s;
            return d.innerHTML;
        }

        // ── Also update Archivos tab badge ──
        function updateFilesBadge(count) {
            const archivosTab = document.getElementById('tab-archivos');
            if (count > 0) {
                archivosTab.innerHTML = `<i class="bi bi-paperclip"></i> Archivos <span class="badge bg-secondary rounded-pill ms-1" style="font-size:0.65rem;">${count}</span>`;
            }
        }

        // ── Initial load ──
        loadMessages();

        // Load messages when tab is shown
        document.getElementById('tab-conversacion').addEventListener('shown.bs.tab', () => {
            loadMessages();
        });

        // ═══════════════════════════════════════════════════
        // ▌ MEDIA LIGHTBOX
        // ═══════════════════════════════════════════════════
        const lbOverlay = document.getElementById('lightbox-overlay');
        const lbImg = document.getElementById('lightbox-img');
        const lbVid = document.getElementById('lightbox-vid');
        const lbClose = document.getElementById('lightbox-close');

        function openLightbox(src, isVideo = false) {
            if (isVideo) {
                lbImg.style.display = 'none';
                lbVid.style.display = 'block';
                lbVid.src = src;
                lbVid.play().catch(e => console.log("Auto-play prevented"));
            } else {
                lbVid.style.display = 'none';
                lbVid.pause();
                lbVid.src = '';
                lbImg.style.display = 'block';
                lbImg.src = src;
            }
            lbOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeLightbox() {
            lbOverlay.classList.remove('active');
            lbImg.src = '';
            lbVid.pause();
            lbVid.src = '';
            lbVid.style.display = 'none';
            document.body.style.overflow = '';
        }

        lbClose.addEventListener('click', closeLightbox);
        lbOverlay.addEventListener('click', (e) => {
            if (e.target === lbOverlay) closeLightbox();
        });
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && lbOverlay.classList.contains('active')) closeLightbox();
        });

        // Attach lightbox to dynamically rendered media
        document.addEventListener('click', (e) => {
            const img = e.target.closest('.conv-msg-media img, .report-attached-img');
            if (img) {
                e.preventDefault();
                openLightbox(img.src, false);
                return;
            }

            const vidWrapper = e.target.closest('.conv-msg-vid, .report-attached-vid');
            if (vidWrapper && vidWrapper.dataset.url) {
                e.preventDefault();
                openLightbox(vidWrapper.dataset.url, true);
                return;
            }

            // Also lightbox for file-card clicks
            const fcThumb = e.target.closest('.file-card-thumb');
            if (fcThumb && fcThumb.dataset.url) {
                e.preventDefault();
                if (fcThumb.hasAttribute('data-is-doc')) {
                    window.open(fcThumb.dataset.url, '_blank');
                } else {
                    openLightbox(fcThumb.dataset.url, fcThumb.hasAttribute('data-is-video'));
                }
                return;
            }
        });

        // ═══════════════════════════════════════════════════
        // ▌ ARCHIVOS MODULE
        // ═══════════════════════════════════════════════════
        const filesGrid = document.getElementById('files-grid');
        const filesEmpty = document.getElementById('files-empty');
        const filesBadge = document.getElementById('files-total-badge');
        const viewGridBtn = document.getElementById('files-view-grid');
        const viewListBtn = document.getElementById('files-view-list');
        const filesUpload = document.getElementById('files-upload-input');

        // Aggregate initial ticket files
        const ticketMediaUrls = <?= json_encode(
            array_map(function ($url) {
            return [
                'url' => base_url('admin/tickets/media/' . basename($url)),
                'name' => basename($url),
                'uploader' => esc($ticket['reporter'] ?? 'Residente'),
            ];
        }, is_array($ticket['media_urls'] ?? null) ? $ticket['media_urls'] : (json_decode($ticket['media_urls'] ?? '[]', true) ?? []))
        ) ?>;

        let allFiles = [];

        function loadAllFiles() {
            // Start with ticket files
            allFiles = ticketMediaUrls.map(f => {
                const isVid = /\.(mp4|mov|webm)$/i.test(f.name);
                const isPdf = /\.pdf$/i.test(f.name);
                const isDoc = /\.(doc|docx|xls|xlsx|ppt|pptx|txt)$/i.test(f.name);
                return {
                    url: f.url,
                    name: f.name,
                    uploader: f.uploader,
                    isVideo: isVid,
                    isPdf: isPdf,
                    isDoc: isDoc,
                    isImage: !isVid && !isPdf && !isDoc
                };
            });

            // Add conversation files
            allMessages.forEach(m => {
                if (m.media_urls && m.media_urls.length > 0) {
                    m.media_urls.forEach(url => {
                        const name = url.split('/').pop();
                        const isVid = /\.(mp4|mov|webm)$/i.test(name);
                        const isPdf = /\.pdf$/i.test(name);
                        const isDoc = /\.(doc|docx|xls|xlsx|ppt|pptx|txt)$/i.test(name);
                        allFiles.push({
                            url: url,
                            name: name,
                            uploader: m.name,
                            isVideo: isVid,
                            isPdf: isPdf,
                            isDoc: isDoc,
                            isImage: !isVid && !isPdf && !isDoc
                        });
                    });
                }
            });

            renderFiles();
        }

        function renderFiles() {
            // Update badge counters
            const count = allFiles.length;
            filesBadge.textContent = count;
            updateFilesBadge(count);

            // Clear previous cards (keep empty placeholder)
            filesGrid.querySelectorAll('.file-card').forEach(el => el.remove());

            if (count === 0) {
                filesEmpty.style.display = '';
                return;
            }
            filesEmpty.style.display = 'none';

            allFiles.forEach(f => {
                const card = document.createElement('div');
                card.className = 'file-card';

                let thumbHtml = '';
                let overlayIcon = '';

                if (f.isVideo) {
                    thumbHtml = `<video src="${escHtml(f.url)}" muted preload="metadata"></video>`;
                    overlayIcon = 'play-circle';
                } else if (f.isPdf) {
                    thumbHtml = `<div class="d-flex align-items-center justify-content-center h-100 bg-light text-danger" style="font-size: 3.5rem;"><i class="bi bi-file-earmark-pdf-fill"></i></div>`;
                    overlayIcon = 'box-arrow-up-right';
                } else if (f.isDoc) {
                    thumbHtml = `<div class="d-flex align-items-center justify-content-center h-100 bg-light text-primary" style="font-size: 3.5rem;"><i class="bi bi-file-earmark-text-fill"></i></div>`;
                    overlayIcon = 'box-arrow-up-right';
                } else {
                    thumbHtml = `<img src="${escHtml(f.url)}" alt="${escHtml(f.name)}">`;
                    overlayIcon = 'zoom-in';
                }

                card.innerHTML = `
                    <div class="file-card-thumb" data-url="${escHtml(f.url)}" ${f.isVideo ? 'data-is-video="1"' : ''} ${!f.isVideo && !f.isImage ? 'data-is-doc="1"' : ''}>
                        ${thumbHtml}
                        <div class="file-card-overlay">
                            <i class="bi bi-${overlayIcon}"></i>
                            ${f.isImage || f.isVideo ? 'Haz clic para ver en tamaño completo' : 'Haz clic para abrir en nueva pestaña'}
                        </div>
                    </div>
                    <div class="file-card-info">
                        <div class="file-card-uploader">${escHtml(f.uploader)}</div>
                    </div>
                    <a class="file-card-download" href="${escHtml(f.url)}" download="${escHtml(f.name)}">
                        <i class="bi bi-download"></i> Descargar
                    </a>
                `;

                filesGrid.appendChild(card);
            });
        }

        // Grid/List toggle
        viewGridBtn.addEventListener('click', () => {
            filesGrid.classList.remove('list-mode');
            viewGridBtn.classList.add('active');
            viewListBtn.classList.remove('active');
        });
        viewListBtn.addEventListener('click', () => {
            filesGrid.classList.add('list-mode');
            viewListBtn.classList.add('active');
            viewGridBtn.classList.remove('active');
        });

        // Upload from Archivos tab
        filesUpload.addEventListener('change', async () => {
            const uploadFiles = Array.from(filesUpload.files);
            if (uploadFiles.length === 0) return;

            const fd = new FormData();
            fd.append('message', '');
            fd.append('type', 'reply');
            uploadFiles.forEach(f => fd.append('media[]', f));

            try {
                const res = await fetch(CONV_API, { method: 'POST', body: fd });
                const data = await res.json();
                if (data.status === 201) {
                    await loadMessages(); // refresh messages & files
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Archivo(s) subido(s)', showConfirmButton: false, timer: 2000 });
                    }
                }
            } catch (e) {
                if (typeof Swal !== 'undefined') Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'Error al subir archivo', showConfirmButton: false, timer: 3000 });
            }
            filesUpload.value = '';
        });

        // Load files when messages load
        const origLoadMessages = loadMessages;
        loadMessages = async function () {
            await origLoadMessages();
            loadAllFiles();
        };

        // Load files when tab is shown
        document.getElementById('tab-archivos').addEventListener('shown.bs.tab', () => {
            loadAllFiles();
        });

        // Initial file load
        loadAllFiles();
    });
</script>

<?= $this->endSection() ?>