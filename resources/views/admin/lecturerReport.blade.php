@extends((Auth::user()->usrtype == "ADM") ? 'layouts.admin' : (Auth::user()->usrtype == "RGS" ? 'layouts.pendaftar' : ''))

@section('main')
<style>
  /* Modern, Fun and Interactive CSS with Animations */
:root {
    /* Main color palette - Vibrant and modern */
    --primary-color: #6366f1;
    --primary-light: #818cf8;
    --primary-dark: #4f46e5;
    --secondary-color: #ec4899;
    --secondary-light: #f472b6;
    --secondary-dark: #db2777;
    --accent-color: #06b6d4;
    --accent-light: #22d3ee;
    --accent-dark: #0891b2;
    
    /* UI Elements */
    --folder-color: #f43f5e;
    --folder-hover: #e11d48;
    --file-color: #10b981;
    --file-hover: #059669;
    --video-color: #f59e0b;
    --video-hover: #d97706;
    --link-color: #3b82f6;
    --link-hover: #2563eb;
    
    /* Entity colors */
    --faculty-color: #8b5cf6;
    --lecturer-color: #3b82f6;
    --course-color: #f43f5e;
    
    /* Background and text */
    --card-bg: #ffffff;
    --bg-light: #f8fafc;
    --bg-dark: #0f172a;
    --text-color: #000000 !important;
    --text-light: #000000 !important;
    --text-white: #f8fafc;
    
    /* Shadows and effects */
    --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    --hover-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    --btn-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    
    /* Animation timing */
    --transition-fast: 0.2s;
    --transition-medium: 0.3s;
    --transition-slow: 0.5s;
}

body {
    font-family: 'Plus Jakarta Sans', 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
    background-color: var(--bg-light);
    color: var(--text-color);
    overflow-x: hidden;
}

/* Animated background effect */
.content-wrapper {
    background: linear-gradient(135deg, #f8fafc 0%, #eff6ff 100%);
    min-height: 100vh;
    position: relative;
    overflow: hidden;
}

.content-wrapper::before {
    content: "";
    position: fixed;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    background: radial-gradient(circle at 10% 10%, rgba(99, 102, 241, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 90% 30%, rgba(236, 72, 153, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 50% 80%, rgba(6, 182, 212, 0.03) 0%, transparent 50%);
    z-index: 0;
    pointer-events: none;
}

.content-wrapper::after {
    content: "";
    position: fixed;
    width: 300px;
    height: 300px;
    border-radius: 50%;
    background: linear-gradient(135deg, rgba(99, 102, 241, 0.05), rgba(236, 72, 153, 0.05));
    top: -150px;
    right: -150px;
    z-index: 0;
    animation: floating 20s ease-in-out infinite alternate;
    pointer-events: none;
}

@keyframes floating {
    0% {
        transform: translate(0, 0) scale(1);
    }
    50% {
        transform: translate(-30px, 30px) scale(1.2);
    }
    100% {
        transform: translate(10px, -20px) scale(0.9);
    }
}

/* Page Header */
.page-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    color: white;
    border-radius: 20px;
    padding: 30px 35px;
    margin-bottom: 35px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(99, 102, 241, 0.2);
    backdrop-filter: blur(5px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    z-index: 1;
    animation: headerAppear 0.8s cubic-bezier(0.22, 1, 0.36, 1) forwards;
}

@keyframes headerAppear {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.page-header::before {
    content: "";
    position: absolute;
    top: -50%;
    right: -20%;
    width: 80%;
    height: 200%;
    background: linear-gradient(rgba(255, 255, 255, 0.1), transparent);
    transform: rotate(30deg);
    z-index: -1;
}

.page-header::after {
    content: "";
    position: absolute;
    bottom: -30%;
    left: -10%;
    width: 60%;
    height: 150%;
    background: linear-gradient(transparent, rgba(255, 255, 255, 0.1));
    transform: rotate(-25deg);
    z-index: -1;
}

.page-header .page-title {
    font-weight: 700;
    margin-bottom: 15px;
    font-size: 28px;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    position: relative;
}

.page-header .page-title::after {
    content: "";
    position: absolute;
    bottom: -8px;
    left: 0;
    width: 60px;
    height: 3px;
    background: var(--accent-light);
    border-radius: 3px;
}

.breadcrumb {
    background: transparent;
    padding: 0;
    position: relative;
    z-index: 2;
}

.breadcrumb-item a, .breadcrumb-item {
    color: rgba(255, 255, 255, 0.85);
    font-weight: 500;
    transition: all var(--transition-fast) ease;
}

.breadcrumb-item a:hover {
    color: #fff;
    text-decoration: none;
}

.breadcrumb-item.active {
    color: #fff;
    font-weight: 600;
}

.breadcrumb-item + .breadcrumb-item::before {
    color: rgba(255, 255, 255, 0.6);
}

/* Main Content Card */
.material-card {
    transition: all var(--transition-medium) cubic-bezier(0.22, 1, 0.36, 1);
    border-radius: 20px;
    overflow: hidden;
    background-color: var(--card-bg);
    box-shadow: var(--card-shadow);
    border: none;
    position: relative;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.5);
    animation: cardAppear 0.8s cubic-bezier(0.22, 1, 0.36, 1) forwards;
    animation-delay: 0.2s;
    opacity: 0;
}

@keyframes cardAppear {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.material-card:hover {
    box-shadow: var(--hover-shadow);
}

.material-card .card-header {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    border: none;
    color: white;
    padding: 22px 25px;
    font-weight: 600;
    letter-spacing: 0.5px;
    position: relative;
    overflow: hidden;
}

.material-card .card-header::after {
    content: "";
    position: absolute;
    top: 0;
    right: 0;
    width: 150px;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
    transform: skewX(-30deg);
    animation: shimmer 3s infinite;
}

@keyframes shimmer {
    0% {
        transform: translateX(-200%) skewX(-30deg);
    }
    100% {
        transform: translateX(300%) skewX(-30deg);
    }
}

.material-card .card-title {
    margin: 0;
    font-weight: 700;
    font-size: 20px;
    display: flex;
    align-items: center;
}

.material-card .card-title i {
    margin-right: 12px;
    font-size: 22px;
}

.material-card .card-body {
    padding: 0;
}

/* Faculty Tree Section */
.faculty-tree-container {
    padding: 20px;
    background: linear-gradient(135deg, var(--card-bg) 0%, rgba(248, 250, 252, 0.6) 100%);
    border-right: 1px solid rgba(0, 0, 0, 0.03);
    border-radius: 0;
}

.faculty-tree {
    border-radius: 16px;
    overflow: hidden;
    box-shadow: none;
    border: none;
    height: 100%;
    background-color: transparent;
    animation: fadeIn 0.8s cubic-bezier(0.22, 1, 0.36, 1) forwards;
    animation-delay: 0.3s;
    opacity: 0;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.tree-search {
    position: relative;
    margin-bottom: 20px;
}

.tree-search input {
    width: 100%;
    padding: 12px 20px 12px 45px;
    border-radius: 12px;
    border: 1px solid rgba(0, 0, 0, 0.05);
    background-color: white;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
    transition: all var(--transition-medium) ease;
    font-size: 14px;
}

.tree-search input:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
    border-color: var(--primary-light);
}

.tree-search i {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--primary-light);
    font-size: 16px;
}

/* Tree Items */
.tree-container {
    max-height: 600px;
    overflow-y: auto;
    padding-right: 5px;
}

.tree-container::-webkit-scrollbar {
    width: 6px;
}

.tree-container::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0.03);
    border-radius: 10px;
}

.tree-container::-webkit-scrollbar-thumb {
    background: rgba(99, 102, 241, 0.2);
    border-radius: 10px;
}

.tree-container::-webkit-scrollbar-thumb:hover {
    background: rgba(99, 102, 241, 0.4);
}

.faculty-item,
.lecturer-item,
.course-item {
    margin-bottom: 8px;
    border-radius: 12px;
    transition: all var(--transition-medium) cubic-bezier(0.22, 1, 0.36, 1);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
    overflow: hidden;
}

.faculty-item::before,
.lecturer-item::before,
.course-item::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 0;
    height: 100%;
    background: linear-gradient(90deg, rgba(99, 102, 241, 0.1), transparent);
    transition: width var(--transition-medium) cubic-bezier(0.22, 1, 0.36, 1);
    z-index: 0;
}

.faculty-item:hover::before,
.lecturer-item:hover::before,
.course-item:hover::before {
    width: 100%;
}

.faculty-item {
    padding: 15px 20px;
    background-color: white;
    border-left: 4px solid var(--faculty-color);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

.faculty-item:hover {
    transform: translateX(5px);
    box-shadow: 0 4px 12px rgba(139, 92, 246, 0.1);
}

.lecturer-item {
    padding: 13px 20px 13px 40px;
    background-color: rgba(248, 250, 252, 0.6);
    border-left: 4px solid var(--lecturer-color);
    margin-top: 5px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: nowrap;
}

.lecturer-item:hover {
    transform: translateX(5px);
    background-color: rgba(59, 130, 246, 0.05);
}

.course-item {
    padding: 11px 20px 11px 60px;
    background-color: rgba(248, 250, 252, 0.3);
    border-left: 4px solid var(--course-color);
    margin-top: 2px;
}

.course-item:hover {
    transform: translateX(5px);
    background-color: rgba(244, 63, 94, 0.05);
}

.faculty-item.active,
.lecturer-item.active,
.course-item.active {
    background-color: rgba(99, 102, 241, 0.08);
    transform: translateX(8px);
}

.faculty-item.active {
    box-shadow: 0 4px 15px rgba(139, 92, 246, 0.15);
}

.lecturer-item.active {
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.15);
}

.course-item.active {
    box-shadow: 0 4px 15px rgba(244, 63, 94, 0.15);
}

/* Icons */
.tree-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 10px;
    margin-right: 12px;
    position: relative;
    z-index: 1;
    transition: all var(--transition-medium) cubic-bezier(0.16, 1, 0.3, 1);
}

.tree-icon i {
    font-size: 16px;
    transition: all var(--transition-medium) cubic-bezier(0.16, 1, 0.3, 1);
}

.faculty-icon {
    background: linear-gradient(135deg, rgba(139, 92, 246, 0.1), rgba(139, 92, 246, 0.2));
    color: var(--faculty-color);
}

.lecturer-icon {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(59, 130, 246, 0.2));
    color: var(--lecturer-color);
}

.course-icon {
    background: linear-gradient(135deg, rgba(244, 63, 94, 0.1), rgba(244, 63, 94, 0.2));
    color: var(--course-color);
}

.faculty-item:hover .faculty-icon,
.lecturer-item:hover .lecturer-icon,
.course-item:hover .course-icon {
    transform: scale(1.1) rotate(-5deg);
}

.faculty-item:hover .faculty-icon i,
.lecturer-item:hover .lecturer-icon i,
.course-item:hover .course-icon i {
    transform: scale(1.2);
}

.tree-label {
    font-weight: 500;
    position: relative;
    z-index: 1;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 100%;
    min-width: 0;
    color: #000000 !important;
}

.faculty-item .tree-label {
    font-weight: 600;
    font-size: 15px;
}

.lecturer-item .tree-label {
    font-size: 14px;
    max-width: calc(100% - 50px); /* Ensure there's always space for the status badge */
    overflow: hidden;
    text-overflow: ellipsis;
    color: #000000 !important;
}

.course-item .tree-label {
    font-size: 13px;
    color: var(--text-color);
}

.tree-toggle {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.5);
    color: var(--text-light);
    transition: all var(--transition-medium) cubic-bezier(0.16, 1, 0.3, 1);
    position: relative;
    z-index: 1;
}

.faculty-item:hover .tree-toggle,
.lecturer-item:hover .tree-toggle {
    background-color: white;
    color: var(--primary-color);
    transform: rotate(180deg);
}

.tree-toggle.open {
    transform: rotate(180deg);
    background-color: var(--primary-light);
    color: white;
}

/* Status badges */
.status-badge {
    padding: 5px;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    font-size: 12px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all var(--transition-medium) cubic-bezier(0.16, 1, 0.3, 1);
    position: relative;
    z-index: 1;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    flex-shrink: 0;
}

.status-badge i {
    font-size: 12px;
}

.status-active {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(16, 185, 129, 0.2));
    color: #10b981;
}

.status-inactive {
    background: linear-gradient(135deg, rgba(244, 63, 94, 0.1), rgba(244, 63, 94, 0.2));
    color: #f43f5e;
}

.lecturer-item:hover .status-badge {
    transform: translateX(-5px);
}

/* Content Preview */
.content-preview-container {
    padding: 20px;
}

.content-preview {
    min-height: 400px;
    border-radius: 20px;
    background-color: white;
    box-shadow: var(--card-shadow);
    padding: 25px;
    transition: all var(--transition-medium) cubic-bezier(0.22, 1, 0.36, 1);
    position: relative;
    overflow: hidden;
    animation: contentAppear 0.8s cubic-bezier(0.22, 1, 0.36, 1) forwards;
    animation-delay: 0.4s;
    opacity: 0;
}

@keyframes contentAppear {
    from {
        opacity: 0;
        transform: translateY(20px) scale(0.98);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.content-preview:hover {
    box-shadow: var(--hover-shadow);
}

.content-preview::before {
    content: "";
    position: absolute;
    top: 0;
    right: 0;
    width: 200px;
    height: 200px;
    background: radial-gradient(circle, rgba(99, 102, 241, 0.03) 0%, transparent 70%);
    z-index: 0;
    pointer-events: none;
}

.preview-header {
    padding-bottom: 18px;
    margin-bottom: 25px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
}

.preview-header::after {
    content: "";
    position: absolute;
    bottom: -1px;
    left: 0;
    width: 100px;
    height: 3px;
    background: linear-gradient(90deg, var(--primary-light), transparent);
    border-radius: 3px;
}

.preview-title {
    font-size: 18px;
    font-weight: 600;
    color: var(--text-color);
    margin: 0;
    display: flex;
    align-items: center;
}

.preview-title i {
    margin-right: 10px;
    color: var(--primary-color);
}

/* Empty State */
.preview-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 300px;
    color: var(--text-light);
    text-align: center;
    position: relative;
}

.preview-placeholder i {
    font-size: 60px;
    margin-bottom: 20px;
    color: rgba(99, 102, 241, 0.2);
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-10px);
    }
}

.preview-placeholder h5 {
    font-weight: 600;
    color: var(--text-color);
    margin-bottom: 10px;
}

.preview-placeholder p {
    max-width: 80%;
    margin: 0 auto;
}

/* Material Items */
.material-item {
    padding: 16px;
    border-radius: 12px;
    background-color: rgba(248, 250, 252, 0.6);
    margin-bottom: 15px;
    transition: all var(--transition-medium) cubic-bezier(0.22, 1, 0.36, 1);
    display: flex;
    align-items: center;
    position: relative;
    overflow: hidden;
}

.material-item::before {
    content: "";
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 4px;
    background-color: transparent;
    transition: all var(--transition-medium) cubic-bezier(0.22, 1, 0.36, 1);
}

.material-item:hover {
    background-color: white;
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
}

.material-item:hover::before {
    background-color: var(--primary-color);
}

.material-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    margin-right: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all var(--transition-medium) cubic-bezier(0.22, 1, 0.36, 1);
    position: relative;
    z-index: 1;
}

.material-item:hover .material-icon {
    transform: scale(1.1);
}

.material-icon.folder {
    background: linear-gradient(135deg, rgba(244, 63, 94, 0.1), rgba(244, 63, 94, 0.2));
    color: var(--folder-color);
}

.material-icon.file {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(16, 185, 129, 0.2));
    color: var(--file-color);
}

.material-icon.video {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(245, 158, 11, 0.2));
    color: var(--video-color);
}

.material-icon i {
    font-size: 22px;
    transition: all var(--transition-medium) cubic-bezier(0.22, 1, 0.36, 1);
}

.material-item:hover .material-icon i {
    transform: scale(1.2);
}

.material-details {
    flex: 1;
}

.material-title {
    font-weight: 600;
    margin-bottom: 4px;
    transition: all var(--transition-medium) cubic-bezier(0.22, 1, 0.36, 1);
    color: #000000 !important;
}

.material-item:hover .material-title {
    color: var(--primary-color);
}

.material-meta {
    font-size: 12px;
    color: var(--text-light);
    display: flex;
    align-items: center;
    color: #000000 !important;
    opacity: 0.8;
}

.material-meta i {
    margin-right: 5px;
    font-size: 11px;
}

.material-meta span {
    margin-right: 15px;
}

.material-action {
    display: flex;
    align-items: center;
    opacity: 0;
    transform: translateX(10px);
    transition: all var(--transition-medium) cubic-bezier(0.22, 1, 0.36, 1);
}

.material-item:hover .material-action {
    opacity: 1;
    transform: translateX(0);
}

.action-btn {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    background-color: white;
    color: var(--text-color);
    margin-left: 8px;
    transition: all var(--transition-medium) cubic-bezier(0.22, 1, 0.36, 1);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.action-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
}

.action-btn.preview:hover {
    background-color: var(--primary-color);
    color: white;
}

.action-btn.download:hover {
    background-color: var(--secondary-color);
    color: white;
}

/* Loading Animation */
.loading-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 300px;
    flex-direction: column;
}

.spinner {
    width: 40px;
    height: 40px;
    position: relative;
    margin-bottom: 20px;
}

.double-bounce1, .double-bounce2 {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background-color: var(--primary-color);
    opacity: 0.6;
    position: absolute;
    top: 0;
    left: 0;
    animation: sk-bounce 2.0s infinite ease-in-out;
}

.double-bounce2 {
    animation-delay: -1.0s;
    background-color: var(--secondary-color);
}

@keyframes sk-bounce {
    0%, 100% { 
        transform: scale(0.0);
    } 
    50% { 
        transform: scale(1.0);
    }
}

.loading-text {
    color: var(--text-color);
    font-weight: 500;
    position: relative;
    color: #000000 !important;
}

.loading-text::after {
    content: '...';
    position: absolute;
    animation: loading-dots 1.5s infinite;
}

@keyframes loading-dots {
    0% {
        content: '.';
    }
    33% {
        content: '..';
    }
    66% {
        content: '...';
    }
}

/* Animations */
.fade-in {
    animation: fadeInEffect 0.5s cubic-bezier(0.22, 1, 0.36, 1) forwards;
}

@keyframes fadeInEffect {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.fade-in-delay-1 {
    animation-delay: 0.1s;
}

.fade-in-delay-2 {
    animation-delay: 0.2s;
}

.fade-in-delay-3 {
    animation-delay: 0.3s;
}

.slide-in-right {
    animation: slideInRight 0.5s cubic-bezier(0.22, 1, 0.36, 1) forwards;
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.scale-in {
    animation: scaleIn 0.5s cubic-bezier(0.22, 1, 0.36, 1) forwards;
}

@keyframes scaleIn {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.pop-in {
    animation: popIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

@keyframes popIn {
    0% {
        opacity: 0;
        transform: scale(0.5);
    }
    70% {
        transform: scale(1.1);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}

/* Hover animations */
.hover-lift {
    transition: transform var(--transition-medium) cubic-bezier(0.22, 1, 0.36, 1);
}

.hover-lift:hover {
    transform: translateY(-5px);
}

.hover-scale {
    transition: transform var(--transition-medium) cubic-bezier(0.22, 1, 0.36, 1);
}

.hover-scale:hover {
    transform: scale(1.05);
}

.hover-rotate {
    transition: transform var(--transition-medium) cubic-bezier(0.22, 1, 0.36, 1);
}

.hover-rotate:hover {
    transform: rotate(3deg);
}

/* Pulse animation on action buttons */
.pulse-effect {
    position: relative;
}

.pulse-effect::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border-radius: inherit;
    animation: pulse-animation 2s infinite;
    z-index: -1;
}

@keyframes pulse-animation {
    0% {
        box-shadow: 0 0 0 0 rgba(99, 102, 241, 0.5);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(99, 102, 241, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(99, 102, 241, 0);
    }
}

/* Ripple effect */
.ripple {
    position: relative;
    overflow: hidden;
}

.ripple::after {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: rgba(255, 255, 255, 0.4);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    opacity: 0;
    transition: width 0.5s, height 0.5s, opacity 0.5s;
}

.ripple:active::after {
    width: 300px;
    height: 300px;
    opacity: 1;
    transition: 0s;
}

/* Motion/parallax effect for preview panels */
.parallax-container {
    perspective: 1000px;
    transform-style: preserve-3d;
}

.parallax-item {
    transition: transform 0.2s ease-out;
}

/* Glowing effect for important elements */
.glow-effect {
    position: relative;
}

.glow-effect::before {
    content: '';
    position: absolute;
    top: -2px;
    left: -2px;
    right: -2px;
    bottom: -2px;
    background: linear-gradient(45deg, 
        var(--primary-color), 
        var(--accent-color), 
        var(--secondary-color), 
        var(--primary-color));
    border-radius: inherit;
    z-index: -1;
    animation: glowing 3s linear infinite;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.glow-effect:hover::before {
    opacity: 1;
}

@keyframes glowing {
    0% {
        background-position: 0 0;
    }
    50% {
        background-position: 400% 0;
    }
    100% {
        background-position: 0 0;
    }
}

/* Typewriter effect for titles */
.typewriter {
    overflow: hidden;
    border-right: 3px solid var(--primary-color);
    white-space: nowrap;
    margin: 0 auto;
    animation: typing 3.5s steps(40, end), blink-caret 0.75s step-end infinite;
}

@keyframes typing {
    from { width: 0 }
    to { width: 100% }
}

@keyframes blink-caret {
    from, to { border-color: transparent }
    50% { border-color: var(--primary-color) }
}

/* Status cards with animated background */
.status-card {
    border-radius: 16px;
    background: white;
    padding: 20px;
    box-shadow: var(--card-shadow);
    position: relative;
    overflow: hidden;
    z-index: 1;
}

.status-card::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: conic-gradient(
        transparent, 
        rgba(99, 102, 241, 0.03), 
        transparent, 
        rgba(236, 72, 153, 0.03), 
        transparent
    );
    animation: rotate-bg 10s linear infinite;
    z-index: -1;
}

@keyframes rotate-bg {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

/* Notification badges */
.notification-badge {
    position: relative;
}

.notification-badge::after {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 10px;
    height: 10px;
    background-color: var(--secondary-color);
    border-radius: 50%;
    transform: translate(50%, -50%);
    animation: notification-pulse 1.5s infinite;
}

@keyframes notification-pulse {
    0% {
        transform: translate(50%, -50%) scale(1);
        opacity: 1;
    }
    70% {
        transform: translate(50%, -50%) scale(1.5);
        opacity: 0.7;
    }
    100% {
        transform: translate(50%, -50%) scale(1);
        opacity: 1;
    }
}

/* Gradient text */
.gradient-text {
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    display: inline-block;
}

/* Loading skeleton animation */
.skeleton {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: skeleton-loading 1.5s infinite;
    border-radius: 4px;
}

@keyframes skeleton-loading {
    0% {
        background-position: 200% 0;
    }
    100% {
        background-position: -200% 0;
    }
}

/* Progress bars with animation */
.progress-bar {
    height: 8px;
    border-radius: 4px;
    background: #f0f0f0;
    overflow: hidden;
    position: relative;
}

.progress-bar::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    width: var(--progress, 0%);
    background: linear-gradient(90deg, var(--primary-color), var(--primary-light));
    border-radius: 4px;
    transition: width 1s cubic-bezier(0.22, 1, 0.36, 1);
}

/* Fluid blob animation in background */
.fluid-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    z-index: -1;
}

.blob {
    position: absolute;
    background: radial-gradient(circle, rgba(99, 102, 241, 0.05) 0%, transparent 70%);
    border-radius: 50%;
    transform-origin: center center;
    opacity: 0.7;
}

.blob-1 {
    top: -150px;
    left: -150px;
    width: 300px;
    height: 300px;
    animation: blob-movement 15s ease-in-out infinite alternate;
}

.blob-2 {
    bottom: -200px;
    right: -100px;
    width: 400px;
    height: 400px;
    animation: blob-movement 20s ease-in-out infinite alternate-reverse;
}

@keyframes blob-movement {
    0% {
        transform: translate(0, 0) scale(1);
    }
    33% {
        transform: translate(50px, 30px) scale(1.1);
    }
    66% {
        transform: translate(-30px, 50px) scale(0.9);
    }
    100% {
        transform: translate(20px, -20px) scale(1.05);
    }
}

/* Additional responsive adjustments */
@media (max-width: 768px) {
    .faculty-tree-container {
        margin-bottom: 20px;
    }
    
    .page-header {
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .material-item {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .material-icon {
        margin-bottom: 10px;
    }
    
    .material-action {
        margin-top: 10px;
        opacity: 1;
        transform: none;
    }
}

/* Dark mode support (if needed) */
@media (prefers-color-scheme: dark) {
    :root {
        --card-bg: #1e293b;
        --bg-light: #0f172a;
        --text-color: #e2e8f0;
        --text-light: #94a3b8;
    }
    
    .faculty-item,
    .lecturer-item,
    .course-item,
    .material-item {
        background-color: rgba(30, 41, 59, 0.5);
    }
    
    .tree-search input {
        background-color: #1e293b;
        border-color: #334155;
        color: #e2e8f0;
    }
    
    .action-btn {
        background-color: #334155;
        color: #e2e8f0;
    }
    
    .skeleton {
        background: linear-gradient(90deg, #334155 25%, #1e293b 50%, #334155 75%);
    }
}

/* Add text color overrides */
.tree-label,
.material-title,
.material-meta,
.preview-title,
.preview-placeholder h5,
.preview-placeholder p,
.loading-text,
.faculty-item,
.lecturer-item,
.course-item,
.material-item {
    color: #000000 !important;
}

.material-meta,
.text-muted {
    color: #000000 !important;
    opacity: 0.8;
}
</style>
<!-- Content Wrapper -->
<div class="content-wrapper">
  <div class="container-full">
    <!-- Page Header -->
    <div class="page-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h3 class="page-title">Lecturer Report Dashboard</h3>
          <div class="d-inline-block align-items-center">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item">Admin</li>
                <li class="breadcrumb-item active" aria-current="page">Report</li>
              </ol>
            </nav>
          </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="d-flex">
          <div class="stat-card pulse-effect me-3">
            <div class="d-flex align-items-center">
              <div class="icon-container">
                <i class="ti-user"></i>
              </div>
              <div class="stats-data">
                @php
                  $lecturerCount = 0;
                  foreach ($lecturer as $facultyLecturers) {
                    $lecturerCount += count($facultyLecturers);
                  }
                @endphp
                <span class="stats-number">{{ $lecturerCount }}</span>
                <span class="stats-label">Lecturers</span>
              </div>
            </div>
          </div>
          <div class="stat-card me-3">
            <div class="d-flex align-items-center">
              <div class="icon-container">
                <i class="ti-book"></i>
              </div>
              <div class="stats-data">
                @php
                  $courseCount = 0;
                  foreach ($course as $facultyCourses) {
                    foreach ($facultyCourses as $lecturerCourses) {
                      $courseCount += count($lecturerCourses);
                    }
                  }
                @endphp
                <span class="stats-number">{{ $courseCount }}</span>
                <span class="stats-label">Courses</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-lg-12 mb-4">
          <!-- Session Filter Card -->
          <div class="material-card">
            <div class="card-header">
              <div class="card-title mb-0">
                <i class="ti-filter"></i>
                Filter Options
              </div>
            </div>
            <div class="card-body">
              <div class="row align-items-center">
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="form-label">Academic Session</label>
                    <select id="sessionFilter" class="form-control">
                      <option value="all">All Sessions</option>
                      @foreach ($session as $ses)
                        <option value="{{ $ses->SessionID }}">{{ $ses->SessionName }} ({{ $ses->Year }})</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="form-label">Status</label>
                    <select id="statusFilter" class="form-control">
                      <option value="all">All Status</option>
                      <option value="NOTACTIVE">Not Active</option>
                      <option value="ACTIVE">Active</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                  <button type="button" id="resetFilters" class="btn btn-light-secondary">
                    <i class="ti-reload"></i> Reset Filters
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-lg-12">
          <div class="material-card">
            <div class="card-header">
              <div class="card-title mb-0">
                <i class="ti-dashboard"></i>
                Lecturer's Course Content Explorer
              </div>
            </div>
            <div class="card-body p-0">
              <div class="row mx-0">
                <!-- Faculty Tree -->
                <div class="col-lg-4 faculty-tree-container">
                  <div class="tree-search mb-4">
                    <i class="ti-search"></i>
                    <input type="text" placeholder="Search faculty, lecturer or course..." id="searchInput">
                  </div>
                  
                  <div class="tree-container" id="facultyTree">
                    @foreach ($faculty as $key => $fcl)
                    <div class="faculty-group fade-in fade-in-delay-1" data-filter-item data-filter-name="{{ strtolower($fcl->facultyname) }}">
                      <div class="faculty-item ripple" data-toggle="faculty-{{ $fcl->id }}">
                        <div class="d-flex align-items-center">
                          <span class="tree-icon faculty-icon">
                            <i class="ti-book"></i>
                          </span>
                          <span class="tree-label">{{ $fcl->facultyname }}</span>
                        </div>
                        <span class="tree-toggle">
                          <i class="fa fa-chevron-down"></i>
                        </span>
                      </div>
                      
                      <div id="faculty-{{ $fcl->id }}" class="faculty-children" style="display:none;">
                        @foreach ($lecturer[$key] as $key2 => $lct)
                        <div class="lecturer-group" data-filter-item data-filter-name="{{ strtolower($lct->name) }}">
                          <div class="lecturer-item ripple" data-toggle="lecturer-{{ $lct->ic }}">
                            <div class="d-flex align-items-center flex-grow-1 min-width-0" style="max-width: calc(100% - 40px);">
                              <span class="tree-icon lecturer-icon flex-shrink-0">
                                <i class="ti-user"></i>
                              </span>
                              <span class="tree-label text-truncate" title="{{ $lct->name }}">{{ $lct->name }}</span>
                            </div>
                            <div class="flex-shrink-0 ms-2" style="min-width: 30px;">
                              @if($lct->lastLogin != null)
                              <span class="status-badge status-active cursor-pointer" title="Last Login: {{ $lct->lastLogin }}" onclick="getUser('{{ $lct->ic }}')" data-toggle="modal" data-target="#userLog">
                                <i class="fa fa-check-circle"></i>
                              </span>
                              @else
                              <span class="status-badge status-inactive cursor-pointer" title="Not Active" data-toggle="modal" data-target="#userLog">
                                <i class="fa fa-times-circle"></i>
                              </span>
                              @endif
                            </div>
                          </div>
                          
                          <div id="lecturer-{{ $lct->ic }}" class="lecturer-children" style="display:none;">
                            @foreach ($course[$key][$key2] as $key3 => $crs)
                            <div class="course-item ripple hover-lift" 
                                data-filter-item 
                                data-filter-name="{{ strtolower($crs->course_name) }} {{ strtolower($crs->course_code) }}"
                                data-session="{{ $crs->SessionID }}"
                                data-status="{{ isset($crs->Status) ? $crs->Status : 'NOTACTIVE' }}"
                                onclick="tryerr0('{{ $crs->subject_id }}','{{ $lct->ic }}','{{ $crs->SessionID }}')">
                              <span class="tree-icon course-icon">
                                <i class="ti-folder"></i>
                              </span>
                              <span class="tree-label">{{ $crs->course_name }} <small>({{ $crs->course_code }} / {{ $crs->SessionName }})</small></span>
                            </div>
                            @endforeach
                          </div>
                        </div>
                        @endforeach
                      </div>
                    </div>
                    @endforeach
                  </div>
                </div>
                
                <!-- Content Preview -->
                <div class="col-lg-8 content-preview-container">
                  <div class="content-preview parallax-container">
                    <div id="showMaterial">
                      <div class="preview-placeholder">
                        <i class="ti-folder-open"></i>
                        <h5>Select a course to view materials</h5>
                        <p class="text-muted">Click on a course from the left panel to explore the content library</p>
                        <div class="mt-4">
                          <div class="quick-tip">
                            <i class="fa fa-lightbulb-o text-warning"></i>
                            <span>Tip: Use the search bar to quickly find courses</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>

<!-- User Log Modal -->
<div id="userLog" class="modal fade" role="dialog">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">User Activity Log</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>From (Start)</label>
              <input type="date" name="from_log" id="from_log" class="form-control">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>To (End)</label>
              <input type="date" name="to_log" id="to_log" class="form-control">
            </div>
          </div>
        </div>
        <hr class="my-3">
        <div class="form-group">
          <label class="form-label">User Log</label>
          <div class="table-responsive">
            <table id="log_list" class="table table-hover">
              <!-- Log content will be loaded here dynamically -->
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Assessment Modal -->
<div id="uploadModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Assessment Report</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>From (Start)</label>
              <input type="date" name="from" id="from" class="form-control">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>To (End)</label>
              <input type="date" name="to" id="to" class="form-control">
            </div>
          </div>
        </div>
        <hr class="my-3">
        <div class="form-group">
          <label class="form-label">Assessment List</label>
          <div class="table-responsive">
            <table id="claim_list" class="table table-hover">
              <!-- Assessment content will be loaded here dynamically -->
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script src="{{ asset('assets/src/js/pages/data-table.js') }}"></script>

<script type="text/javascript">
$(document).ready(function() {
    // Initialize animations
    initAnimations();
    
    // Setup tree toggle functionality
    setupTreeToggle();
    
    // Setup search functionality
    setupSearch();
    
    // Setup parallax effect
    setupParallax();
    
    // Add tooltips
    $('[title]').tooltip();
    
    // Setup session filtering
    setupFilters();
});

function initAnimations() {
    // Reveal items with cascading animation
    $('.faculty-group').each(function(i) {
        $(this).css('animation-delay', (i * 0.1) + 's');
    });
    
    // Add hover effects to cards
    $('.material-card').addClass('hover-lift');
    
    // Initialize stat counters
    animateCounters();
}

function setupTreeToggle() {
    // Tree toggle functionality
    $('[data-toggle]').click(function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const targetId = $(this).attr('data-toggle');
        const $target = $('#' + targetId);
        const $toggle = $(this).find('.tree-toggle');
        
        // Close any open siblings if clicking on a faculty
        if ($(this).hasClass('faculty-item')) {
            const $siblings = $(this).parent().siblings().find('.faculty-children');
            const $siblingToggles = $(this).parent().siblings().find('.faculty-item .tree-toggle');
            
            $siblings.slideUp(300);
            $siblingToggles.removeClass('open');
        }
        
        // Toggle the clicked item
        if ($target.is(':visible')) {
            $target.slideUp(300);
            $toggle.removeClass('open');
            $(this).removeClass('active');
        } else {
            $target.slideDown(300);
            $toggle.addClass('open');
            $(this).addClass('active');
            
            // Load lecturer data if faculty item is clicked
            if ($(this).hasClass('faculty-item')) {
                const id = targetId.split('-')[1];
                getLecturer(id);
            }
            
            // Load course data if lecturer item is clicked
            if ($(this).hasClass('lecturer-item')) {
                const ic = targetId.split('-')[1];
                getSubject(ic);
            }
        }
    });
    
    // Make course items selectable
    $('.course-item').click(function() {
        $('.course-item').removeClass('active');
        $(this).addClass('active');
    });
}

function setupSearch() {
    // Search functionality
    $("#searchInput").on("keyup", function() {
        const value = $(this).val().toLowerCase();
        
        if (value.length > 0) {
            // Hide all groups initially
            $('.faculty-group, .lecturer-group, .course-item').hide();
            
            // Show matching items and their parents
            $('[data-filter-name*="' + value + '"]').each(function() {
                $(this).show();
                
                // If it's a course or lecturer, show its parent
                if ($(this).hasClass('course-item')) {
                    const lecturerId = $(this).parent().attr('id');
                    $('#' + lecturerId).show();
                    $('[data-toggle="' + lecturerId + '"]').parent().show();
                    $('[data-toggle="' + lecturerId + '"]').addClass('active');
                    
                    const facultyId = $('[data-toggle="' + lecturerId + '"]').parent().parent().attr('id');
                    $('#' + facultyId).show();
                    $('[data-toggle="' + facultyId + '"]').parent().show();
                    $('[data-toggle="' + facultyId + '"]').addClass('active');
                }
                
                if ($(this).hasClass('lecturer-group')) {
                    const facultyId = $(this).parent().attr('id');
                    $('#' + facultyId).show();
                    $('[data-toggle="' + facultyId + '"]').parent().show();
                    $('[data-toggle="' + facultyId + '"]').addClass('active');
                }
            });
            
            // Expand all visible items
            $('.faculty-group:visible .faculty-children, .lecturer-group:visible .lecturer-children').show();
            $('.faculty-group:visible .tree-toggle, .lecturer-group:visible .tree-toggle').addClass('open');
        } else {
            // Reset everything when search is cleared
            $('.faculty-group').show();
            $('.lecturer-group, .course-item').hide();
            $('.faculty-children, .lecturer-children').hide();
            $('.tree-toggle').removeClass('open');
            $('.faculty-item, .lecturer-item, .course-item').removeClass('active');
        }
    });
}

function setupFilters() {
    // Apply session filters
    $("#sessionFilter, #statusFilter").on("change", function() {
        applyFilters();
    });
    
    // Reset filters
    $("#resetFilters").click(function() {
        $("#sessionFilter").val("all");
        $("#statusFilter").val("all");
        applyFilters();
    });
}

function applyFilters() {
    const sessionValue = $("#sessionFilter").val();
    const statusValue = $("#statusFilter").val();
    
    // First hide all courses
    $('.course-item').hide();
    
    // Show courses matching the filter criteria
    $('.course-item').each(function() {
        const courseSessionId = $(this).data('session');
        const courseStatus = $(this).data('status');
        
        const sessionMatch = sessionValue === 'all' || courseSessionId.toString() === sessionValue;
        const statusMatch = statusValue === 'all' || courseStatus === statusValue;
        
        if (sessionMatch && statusMatch) {
            $(this).show();
        }
    });
    
    // Show/hide lecturers based on whether they have visible courses
    $('.lecturer-children').each(function() {
        const visibleCourses = $(this).find('.course-item:visible').length;
        const lecturerId = $(this).attr('id');
        
        if (visibleCourses > 0) {
            $('[data-toggle="' + lecturerId + '"]').parent().show();
        } else {
            $('[data-toggle="' + lecturerId + '"]').parent().hide();
        }
    });
    
    // Show/hide faculties based on whether they have visible lecturers
    $('.faculty-children').each(function() {
        const visibleLecturers = $(this).find('.lecturer-group:visible').length;
        const facultyId = $(this).attr('id');
        
        if (visibleLecturers > 0) {
            $('[data-toggle="' + facultyId + '"]').parent().show();
        } else {
            $('[data-toggle="' + facultyId + '"]').parent().hide();
        }
    });
    
    // Expand any faculty or lecturer with visible children
    $('.faculty-group:visible').each(function() {
        const facultyId = $(this).find('.faculty-item').data('toggle');
        if ($(this).find('.lecturer-group:visible').length > 0) {
            $('#' + facultyId).show();
            $('[data-toggle="' + facultyId + '"]').addClass('active');
            $('[data-toggle="' + facultyId + '"]').find('.tree-toggle').addClass('open');
        }
    });
}

function setupParallax() {
    // Subtle parallax effect on content preview
    $('.parallax-container').mousemove(function(e) {
        const $this = $(this);
        const relX = e.pageX - $this.offset().left;
        const relY = e.pageY - $this.offset().top;
        
        const width = $this.width();
        const height = $this.height();
        
        const moveX = (relX - width/2) / width * 5;
        const moveY = (relY - height/2) / height * 5;
        
        $this.find('.parallax-item').css({
            'transform': 'translate3d(' + moveX + 'px, ' + moveY + 'px, 0)'
        });
    });
    
    $('.parallax-container').mouseleave(function() {
        $(this).find('.parallax-item').css({
            'transform': 'translate3d(0, 0, 0)'
        });
    });
}

function animateCounters() {
    // Animate stat counters
    $('.stats-number').each(function() {
        const $this = $(this);
        const countTo = parseInt($this.text());
        
        $({ countNum: 0 }).animate({ countNum: countTo }, {
            duration: 2000,
            easing: 'swing',
            step: function() {
                $this.text(Math.floor(this.countNum));
            },
            complete: function() {
                $this.text(this.countNum);
            }
        });
    });
}

function applyContentAnimations() {
    // Apply animations to dynamically loaded content
    $('#showMaterial').find('.material-item').addClass('fade-in');
    $('#showMaterial').find('.material-item').each(function(i) {
        $(this).css('animation-delay', (i * 0.05) + 's');
    });
    
    // Apply hover effects
    $('#showMaterial').find('.material-icon').addClass('hover-scale');
    $('#showMaterial').find('.action-btn').addClass('hover-lift');
    
    // Make elements with parallax class respond to mouse movement
    $('#showMaterial').find('.parallax-item').addClass('parallax-item');
}

function getLecturer(id) {
    return $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: "{{ url('admin/report/lecturer/getLecturer') }}",
        method: 'POST',
        data: {id: id},
        error: function(err) {
            console.log(err);
            showNotification('Error loading lecturers', 'error');
        },
        success: function(data) {
            // Process lecturer data if needed
        }
    });
}

function getSubject(ic) {
    return $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: "{{ url('admin/report/lecturer/getSubject') }}",
        method: 'POST',
        data: {ic: ic},
        error: function(err) {
            console.log(err);
            showNotification('Error loading subjects', 'error');
        },
        success: function(data) {
            // Process subject data if needed
        }
    });
}

function tryerr0(id, ic, ses) {
    // Show loading animation
    $('#showMaterial').html(`
        <div class="loading-container">
            <div class="spinner">
                <div class="double-bounce1"></div>
                <div class="double-bounce2"></div>
            </div>
            <div class="loading-text">Loading content</div>
        </div>
    `);
    
    return $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: "{{ url('admin/report/lecturer/getFolder') }}",
        method: 'POST',
        data: {id: id, ic: ic, ses: ses},
        error: function(err) {
            console.log(err);
            $('#showMaterial').html(`
                <div class="preview-placeholder">
                    <i class="fa fa-exclamation-triangle text-danger"></i>
                    <h5>Error Loading Content</h5>
                    <p class="text-muted">There was a problem loading the content. Please try again.</p>
                    <button class="btn btn-primary mt-3 ripple" onclick="tryerr0('${id}','${ic}','${ses}')">
                        <i class="fa fa-refresh"></i> Try Again
                    </button>
                </div>
            `);
        },
        success: function(data) {
            $('#showMaterial').html(data);
            
            // Apply animations to loaded content
            setTimeout(function() {
                applyContentAnimations();
            }, 100);
        }
    });
}

function tryerr(id) {
    // Show loading animation
    $('#showMaterial').html(`
        <div class="loading-container">
            <div class="spinner">
                <div class="double-bounce1"></div>
                <div class="double-bounce2"></div>
            </div>
            <div class="loading-text">Loading subfolder</div>
        </div>
    `);
    
    return $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: "{{ url('admin/report/lecturer/getSubfolder') }}",
        method: 'POST',
        data: {id: id},
        error: function(err) {
            console.log(err);
            $('#showMaterial').html(`
                <div class="preview-placeholder">
                    <i class="fa fa-exclamation-triangle text-danger"></i>
                    <h5>Error Loading Subfolder</h5>
                    <p class="text-muted">There was a problem loading the subfolder. Please try again.</p>
                    <button class="btn btn-primary mt-3 ripple" onclick="tryerr('${id}')">
                        <i class="fa fa-refresh"></i> Try Again
                    </button>
                </div>
            `);
        },
        success: function(data) {
            $('#showMaterial').html(data);
            
            // Apply animations to loaded content
            setTimeout(function() {
                applyContentAnimations();
            }, 100);
        }
    });
}

function tryerr2(id) {
    // Show loading animation for subfolder content
    $('#showMaterial').html(`
        <div class="loading-container">
            <div class="spinner">
                <div class="double-bounce1"></div>
                <div class="double-bounce2"></div>
            </div>
            <div class="loading-text">Loading subfolder content</div>
        </div>
    `);
    
    return $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: "{{ url('admin/report/lecturer/getSubfolder/getSubfolder2') }}",
        method: 'POST',
        data: {id: id},
        error: function(err) {
            console.log(err);
            $('#showMaterial').html(`
                <div class="preview-placeholder">
                    <i class="fa fa-exclamation-triangle text-danger"></i>
                    <h5>Error Loading Content</h5>
                    <p class="text-muted">There was a problem loading the subfolder content. Please try again.</p>
                    <button class="btn btn-primary mt-3 ripple" onclick="tryerr2('${id}')">
                        <i class="fa fa-refresh"></i> Try Again
                    </button>
                </div>
            `);
        },
        success: function(data) {
            $('#showMaterial').html(data);
            
            // Apply animations to loaded content
            setTimeout(function() {
                applyContentAnimations();
            }, 100);
        }
    });
}

function tryerr3(id) {
    // Show loading animation for material
    $('#showMaterial').html(`
        <div class="loading-container">
            <div class="spinner">
                <div class="double-bounce1"></div>
                <div class="double-bounce2"></div>
            </div>
            <div class="loading-text">Loading material</div>
        </div>
    `);
    
    return $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: "{{ url('admin/report/lecturer/getSubfolder/getSubfolder2/getMaterial') }}",
        method: 'POST',
        data: {id: id},
        error: function(err) {
            console.log(err);
            $('#showMaterial').html(`
                <div class="preview-placeholder">
                    <i class="fa fa-exclamation-triangle text-danger"></i>
                    <h5>Error Loading Material</h5>
                    <p class="text-muted">There was a problem loading the material. Please try again.</p>
                    <button class="btn btn-primary mt-3 ripple" onclick="tryerr3('${id}')">
                        <i class="fa fa-refresh"></i> Try Again
                    </button>
                </div>
            `);
        },
        success: function(data) {
            $('#showMaterial').html(data);
            
            // Apply animations to loaded content
            setTimeout(function() {
                applyContentAnimations();
            }, 100);
        }
    });
}

let user = null;
let selected_from = null;
let selected_to = null;

function getUser(ic) {
    user = ic;
}

$(document).on('change', '#from_log, #to_log', function() {
    if ($(this).attr('id') === 'from_log') {
        selected_from = $(this).val();
    } else {
        selected_to = $(this).val();
    }
    
    if (selected_from && selected_to && user) {
        getUserLog(selected_from, selected_to);
    }
});

$(document).on('change', '#from, #to', function() {
    if ($(this).attr('id') === 'from') {
        selected_from = $(this).val();
    } else {
        selected_to = $(this).val();
    }
    
    if (selected_from && selected_to) {
        getAssessment(selected_from, selected_to);
    }
});

function getUserLog(from, to) {
    // Show loading spinner in table
    $('#log_list').html('<tr><td colspan="100%" class="text-center"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></td></tr>');
    
    return $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: "{{ url('admin/report/lecturer/getUserLog') }}",
        method: 'POST',
        data: {from: from, to: to, user: user},
        error: function(err) {
            console.log(err);
            $('#log_list').html('<tr><td colspan="100%" class="text-center text-danger">Error loading data. Please try again.</td></tr>');
        },
        success: function(data) {
            $('#log_list').html(data);
        }
    });
}

function getAssessment(from, to) {
    // Show loading spinner in table
    $('#claim_list').html('<tr><td colspan="100%" class="text-center"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></td></tr>');
    
    return $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: "{{ url('admin/report/lecturer/getAssessment') }}",
        method: 'POST',
        data: {from: from, to: to},
        error: function(err) {
            console.log(err);
            $('#claim_list').html('<tr><td colspan="100%" class="text-center text-danger">Error loading data. Please try again.</td></tr>');
        },
        success: function(data) {
            $('#claim_list').html(data);
        }
    });
}

// Add visual feedback for interactions
$(document).on('click', '.ripple', function(e) {
    const $this = $(this);
    
    // Create ripple element
    const $ripple = $('<span class="ripple-effect"></span>');
    const posX = e.pageX - $this.offset().left;
    const posY = e.pageY - $this.offset().top;
    
    $ripple.css({
        top: posY + 'px',
        left: posX + 'px'
    });
    
    $this.append($ripple);
    
    // Remove ripple after animation completes
    setTimeout(function() {
        $ripple.remove();
    }, 600);
});

function showNotification(message, type) {
    // Add notification display logic if needed
    console.log(`[${type}] ${message}`);
}
</script>
@endsection