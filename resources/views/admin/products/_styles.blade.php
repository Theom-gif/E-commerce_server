@push('styles')
<style>
  /* ── Premium Page layout ──────────────────────────────────── */
  @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

  body {
    background: #f4f7fb;
    background-image: 
      radial-gradient(at 0% 0%, hsla(253,16%,7%,0.03) 0, transparent 50%), 
      radial-gradient(at 50% 0%, hsla(225,39%,30%,0.03) 0, transparent 50%), 
      radial-gradient(at 100% 0%, hsla(339,49%,30%,0.03) 0, transparent 50%);
    background-attachment: fixed;
  }

  .product-create {
    max-width: 720px;
    margin: 0 auto;
    padding: 3rem 1.5rem 6rem;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    animation: fadeSlideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    opacity: 0;
    transform: translateY(15px);
  }

  @keyframes fadeSlideUp {
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .product-create .page-eyebrow {
    font-size: 12px;
    font-weight: 800;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: #6366f1;
    margin: 0 0 6px;
    display: inline-block;
    background: linear-gradient(135deg, #6366f1, #a855f7);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }

  .product-create .page-title {
    font-size: 28px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 2rem;
    letter-spacing: -0.02em;
  }

  /* ── Form card ────────────────────────────────────── */
  .form-panel {
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.5);
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 20px 40px -10px rgba(15, 23, 42, 0.08);
  }

  /* ── Section labels ───────────────────────────────── */
  .form-section-label {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: #64748b;
    padding: 1.5rem 2rem 0.75rem;
    border-bottom: 1px solid rgba(226, 232, 240, 0.6);
    margin: 0;
    background: rgba(248, 250, 252, 0.4);
  }

  /* ── Fields container ─────────────────────────────── */
  .form-fields {
    padding: 0.5rem 2rem 1rem;
  }

  /* ── Individual field ─────────────────────────────── */
  .field {
    display: flex;
    flex-direction: column;
    gap: 8px;
    padding: 1rem 0;
    border-bottom: 1px solid rgba(226, 232, 240, 0.6);
  }

  .field:last-child {
    border-bottom: none;
  }

  .field .label {
    font-size: 13px;
    font-weight: 600;
    color: #334155;
    letter-spacing: 0.01em;
  }

  .field .label .req {
    color: #ef4444;
    margin-left: 2px;
  }

  /* ── Inputs, select, textarea ─────────────────────── */
  .field input[type="text"],
  .field input[type="number"],
  .field select,
  .field textarea {
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 12px 16px;
    font-size: 14px;
    font-weight: 500;
    font-family: inherit;
    color: #0f172a;
    background: #f8fafc;
    outline: none;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    width: 100%;
    box-sizing: border-box;
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.01);
  }

  .field input[type="text"]:hover,
  .field input[type="number"]:hover,
  .field select:hover,
  .field textarea:hover {
    border-color: #cbd5e1;
    background: #f1f5f9;
  }

  .field input[type="text"]:focus,
  .field input[type="number"]:focus,
  .field select:focus,
  .field textarea:focus {
    border-color: #6366f1;
    background: #ffffff;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
  }

  .field input::placeholder,
  .field textarea::placeholder {
    color: #94a3b8;
    font-weight: 400;
  }

  .field textarea {
    resize: vertical;
    min-height: 110px;
    line-height: 1.6;
  }

  /* Checkbox styling */
  .field input[type="checkbox"] {
    appearance: none;
    background-color: #f8fafc;
    margin: 0;
    font: inherit;
    color: currentColor;
    width: 20px;
    height: 20px;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    display: grid;
    place-content: center;
    transition: all 0.2s ease;
    cursor: pointer;
  }

  .field input[type="checkbox"]::before {
    content: "";
    width: 10px;
    height: 10px;
    transform: scale(0);
    transition: 120ms transform ease-in-out;
    box-shadow: inset 1em 1em white;
    transform-origin: center;
    clip-path: polygon(14% 44%, 0 65%, 50% 100%, 100% 16%, 80% 0%, 43% 62%);
  }

  .field input[type="checkbox"]:checked {
    background-color: #6366f1;
    border-color: #6366f1;
  }

  .field input[type="checkbox"]:checked::before {
    transform: scale(1);
  }

  /* ── Two-column row ────────────────────────────────── */
  .field-row-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    padding: 1rem 2rem;
    border-bottom: 1px solid rgba(226, 232, 240, 0.6);
  }

  .field-row-2 .field {
    padding: 0;
    border-bottom: none;
  }

  /* ── File upload zone ─────────────────────────────── */
  .file-upload-zone {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 8px;
    border: 2px dashed #cbd5e1;
    border-radius: 16px;
    padding: 2.5rem 1rem;
    text-align: center;
    cursor: pointer;
    background: #f8fafc;
    transition: all 0.2s ease;
    position: relative;
    overflow: hidden;
  }

  .file-upload-zone::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(99, 102, 241, 0.05), rgba(168, 85, 247, 0.05));
    opacity: 0;
    transition: opacity 0.3s ease;
  }

  .file-upload-zone:hover {
    border-color: #818cf8;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.08);
  }

  .file-upload-zone:hover::after {
    opacity: 1;
  }

  .file-upload-zone svg {
    color: #64748b;
    transition: color 0.2s ease, transform 0.2s ease;
    position: relative;
    z-index: 1;
  }

  .file-upload-zone:hover svg {
    color: #6366f1;
    transform: translateY(-2px);
  }

  .file-upload-zone .upload-label {
    font-size: 14px;
    font-weight: 600;
    color: #1e293b;
    position: relative;
    z-index: 1;
  }

  .file-upload-zone .upload-hint {
    font-size: 12px;
    color: #94a3b8;
    margin: 0;
    position: relative;
    z-index: 1;
  }

  .file-upload-zone input[type="file"] {
    display: none;
  }
  
  .preview {
    margin-top: 1rem;
  }
  .preview img {
    max-width: 150px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
  }

  /* ── Sticky action bar ────────────────────────────── */
  .form-action-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.25rem 2rem;
    border-top: 1px solid rgba(226, 232, 240, 0.8);
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    position: sticky;
    bottom: 0;
    z-index: 10;
  }

  /* ── Buttons ──────────────────────────────────────── */
  .btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 600;
    font-family: inherit;
    padding: 10px 20px;
    border-radius: 10px;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    line-height: 1.2;
  }

  .btn-secondary {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    color: #475569;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
  }

  .btn-secondary:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
    color: #1e293b;
    transform: translateY(-1px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.04);
  }

  .btn-primary {
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    border: none;
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
  }

  .btn-primary:hover {
    background: linear-gradient(135deg, #4338ca, #6d28d9);
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(79, 70, 229, 0.4);
  }

  .btn-primary:active {
    transform: translateY(0) scale(0.98);
    box-shadow: 0 2px 4px rgba(79, 70, 229, 0.3);
  }
</style>
@endpush
