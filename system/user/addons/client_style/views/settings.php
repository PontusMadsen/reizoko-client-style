<?php echo form_open($base_url, ['class' => 'settings']); ?>

<?= ee('CP/Alert')->getAllInlines() ?>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">

    <div class="panel">
        <div class="panel-heading">
            <div class="title-bar">
                <h3 class="title-bar__title">CSS</h3>
                <div class="title-bar__extra-tools">
                    <a href="#" class="btn btn--default btn--small" id="cs-snippets-btn"><?= lang('common_styles') ?></a>
                </div>
            </div>
        </div>
        <div class="panel-body">

            <fieldset>
                <div class="field-instruct">
                    <label><?= lang('css_url_label') ?></label>
                    <em><?= lang('css_url_desc') ?></em>
                </div>
                <div class="field-control">
                    <input type="text" name="css_url" value="<?= htmlspecialchars($css_url) ?>" placeholder="https://example.com/custom.css">
                </div>
            </fieldset>

            <fieldset>
                <div class="field-instruct">
                    <label><?= lang('custom_css_label') ?></label>
                </div>
                <div class="field-control">
                    <textarea name="custom_css" id="cs-css-textarea" rows="20" style="font-family: monospace; font-size: 13px; tab-size: 4;"><?= htmlspecialchars($custom_css) ?></textarea>
                </div>
            </fieldset>

        </div>
    </div>

    <div class="panel">
        <div class="panel-heading">
            <div class="title-bar">
                <h3 class="title-bar__title">JavaScript</h3>
                <div class="title-bar__extra-tools">
                    <a href="#" class="btn btn--default btn--small" id="js-snippets-btn"><?= lang('common_scripts') ?></a>
                </div>
            </div>
        </div>
        <div class="panel-body">

            <fieldset>
                <div class="field-instruct">
                    <label><?= lang('js_url_label') ?></label>
                    <em><?= lang('js_url_desc') ?></em>
                </div>
                <div class="field-control">
                    <input type="text" name="js_url" value="<?= htmlspecialchars($js_url) ?>" placeholder="https://example.com/custom.js">
                </div>
            </fieldset>

            <fieldset>
                <div class="field-instruct">
                    <label><?= lang('custom_js_label') ?></label>
                </div>
                <div class="field-control">
                    <textarea name="custom_js" id="cs-js-textarea" rows="20" style="font-family: monospace; font-size: 13px; tab-size: 4;"><?= htmlspecialchars($custom_js) ?></textarea>
                </div>
            </fieldset>

        </div>
    </div>

</div>

<div class="panel">
    <div class="panel-footer">
        <div class="form-btns">
            <input class="btn btn--primary" type="submit" value="<?= $save_btn_text ?>" data-submit-text="<?= $save_btn_text ?>" data-work-text="<?= $save_btn_text_working ?>">
        </div>
    </div>
</div>

<!-- Common Styles Modal -->
<div id="cs-modal" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; z-index:10000;">
    <div style="position:absolute; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.5);" id="cs-modal-backdrop"></div>
    <div style="position:relative; margin:5vh auto; width:700px; max-height:90vh; overflow-y:auto; background:#fff; border-radius:6px; box-shadow:0 10px 40px rgba(0,0,0,0.3);">
        <div style="padding:20px 24px; border-bottom:1px solid #e0e0e0; display:flex; justify-content:space-between; align-items:center;">
            <h3 style="margin:0; font-size:16px; font-weight:600;"><?= lang('common_styles') ?></h3>
            <a href="#" id="cs-modal-close" style="font-size:20px; color:#999; text-decoration:none;">&times;</a>
        </div>
        <div style="padding:24px;">
            <p style="color:#666; margin:0 0 16px; font-size:13px;"><?= lang('common_styles_desc') ?></p>

            <div id="cs-snippets" style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
            </div>
        </div>
    </div>
</div>

<!-- Common Scripts Modal -->
<div id="js-modal" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; z-index:10000;">
    <div style="position:absolute; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.5);" id="js-modal-backdrop"></div>
    <div style="position:relative; margin:5vh auto; width:700px; max-height:90vh; overflow-y:auto; background:#fff; border-radius:6px; box-shadow:0 10px 40px rgba(0,0,0,0.3);">
        <div style="padding:20px 24px; border-bottom:1px solid #e0e0e0; display:flex; justify-content:space-between; align-items:center;">
            <h3 style="margin:0; font-size:16px; font-weight:600;"><?= lang('common_scripts') ?></h3>
            <a href="#" id="js-modal-close" style="font-size:20px; color:#999; text-decoration:none;">&times;</a>
        </div>
        <div style="padding:24px;">
            <p style="color:#666; margin:0 0 16px; font-size:13px;"><?= lang('common_scripts_desc') ?></p>

            <div id="js-snippets" style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    // ── JS Snippets ──
    var jsSnippets = [
        {
            label: 'Hide Navigation Item',
            desc: 'Hide a sidebar nav item by name',
            prompt: 'Enter the nav item text to hide (e.g. "Entries", "Files", "Members"):',
            template: function(val) {
                return "var el = document.querySelector('.ee-sidebar__item[title=\"" + val + "\"]');\nif (el) el.style.display = 'none';";
            }
        },
        {
            label: 'Rename Navigation Item',
            desc: 'Change the text of a sidebar nav item',
            prompt: 'Enter the current nav item text:',
            prompt2: 'Enter the new name:',
            template: function(val, val2) {
                return "var el = document.querySelector('.ee-sidebar__item[title=\"" + val + "\"] .ee-sidebar__collapsed-hidden');\nif (el) el.textContent = '" + val2 + "';";
            }
        },
        {
            label: 'Change Sidebar Title',
            desc: 'Replace the site name in sidebar',
            prompt: 'Enter the new sidebar title:',
            template: function(val) {
                return "var t = document.querySelector('.ee-sidebar__title-name');\nif (t) t.innerHTML = t.querySelector('i').outerHTML + ' ' + '" + val + "';";
            }
        },
        {
            label: 'Hide Sidebar Section',
            desc: 'Hide a whole section (Content, Members, Developer)',
            prompt: 'Enter the section label (e.g. "Content", "Members", "Developer"):',
            template: function(val) {
                return "document.querySelectorAll('.ee-sidebar__section-label').forEach(function(el) {\n    if (el.textContent.trim() === '" + val + "') el.closest('.ee-sidebar__items-section').style.display = 'none';\n});";
            }
        },
        {
            label: 'Rename Sidebar Section',
            desc: 'Change a section header (Content, Members, Developer)',
            prompt: 'Enter the current section name:',
            prompt2: 'Enter the new name:',
            template: function(val, val2) {
                return "document.querySelectorAll('.ee-sidebar__section-label').forEach(function(el) {\n    if (el.textContent.trim() === '" + val + "') el.textContent = '" + val2 + "';\n});";
            }
        },
        {
            label: 'Add Welcome Message',
            desc: 'Show a message banner at the top of every page',
            prompt: 'Enter the welcome message:',
            template: function(val) {
                return "var banner = document.createElement('div');\nbanner.style.cssText = 'background:#5D63F1;color:#fff;padding:10px 20px;text-align:center;font-size:14px;';\nbanner.textContent = '" + val + "';\nvar main = document.querySelector('.ee-main');\nif (main) main.prepend(banner);";
            }
        },
        {
            label: 'Confirm Before Deleting',
            desc: 'Extra confirmation on delete actions',
            js: "document.addEventListener('click', function(e) {\n    var btn = e.target.closest('.btn--danger, [data-conditional-modal], .js-modal--destruct');\n    if (btn && !confirm('Are you sure you want to delete this?')) {\n        e.preventDefault();\n        e.stopImmediatePropagation();\n    }\n}, true);"
        },
        {
            label: 'Auto-expand Textarea',
            desc: 'Make all textareas grow with content',
            js: "document.querySelectorAll('textarea').forEach(function(ta) {\n    ta.style.overflow = 'hidden';\n    function resize() { ta.style.height = 'auto'; ta.style.height = ta.scrollHeight + 'px'; }\n    ta.addEventListener('input', resize);\n    resize();\n});"
        }
    ];

    var jsContainer = document.getElementById('js-snippets');
    var jsModal = document.getElementById('js-modal');
    var jsTextarea = document.getElementById('cs-js-textarea');

    jsSnippets.forEach(function(s) {
        var card = document.createElement('div');
        card.style.cssText = 'border:1px solid #ddd; border-radius:4px; padding:14px; cursor:pointer; transition:all 0.15s;';
        card.innerHTML = '<div style="font-weight:600; font-size:14px; margin-bottom:4px;">' + s.label + '</div>' +
                         '<div style="color:#888; font-size:12px;">' + s.desc + '</div>';
        card.addEventListener('mouseenter', function() { card.style.borderColor = '#5D63F1'; card.style.background = '#f8f8ff'; });
        card.addEventListener('mouseleave', function() { card.style.borderColor = '#ddd'; card.style.background = ''; });
        card.addEventListener('click', function() {
            var code;
            if (s.template) {
                var val = prompt(s.prompt);
                if (val === null) return;
                if (s.prompt2) {
                    var val2 = prompt(s.prompt2);
                    if (val2 === null) return;
                    code = s.template(val, val2);
                } else {
                    code = s.template(val);
                }
            } else {
                code = s.js;
            }
            var current = jsTextarea.value.trim();
            var addition = '\n// ' + s.label + '\n' + code + '\n';
            jsTextarea.value = current ? current + '\n' + addition : addition.trim();
            jsModal.style.display = 'none';
            jsTextarea.focus();
            jsTextarea.scrollTop = jsTextarea.scrollHeight;
        });
        jsContainer.appendChild(card);
    });

    document.getElementById('js-snippets-btn').addEventListener('click', function(e) {
        e.preventDefault();
        jsModal.style.display = '';
    });

    document.getElementById('js-modal-close').addEventListener('click', function(e) {
        e.preventDefault();
        jsModal.style.display = 'none';
    });

    document.getElementById('js-modal-backdrop').addEventListener('click', function() {
        jsModal.style.display = 'none';
    });

    // ── CSS Snippets ──
    var snippets = [
        {
            label: 'Sidebar Background',
            desc: 'Change sidebar color',
            css: '.ee-sidebar {\n    background-color: #2a1f4e;\n}'
        },
        {
            label: 'Sidebar Text Color',
            desc: 'Sidebar link and text color',
            css: '.ee-sidebar,\n.ee-sidebar a,\n.ee-sidebar .ee-sidebar__item a {\n    color: #c8d6e5;\n}'
        },
        {
            label: 'Sidebar Title',
            desc: 'Site name in sidebar',
            css: '.ee-sidebar__title {\n    color: #ffffff;\n    font-size: 16px;\n}'
        },
        {
            label: 'Sidebar Active Item',
            desc: 'Highlight color for active nav',
            css: '.ee-sidebar .ee-sidebar__item.active > a {\n    background-color: rgba(255,255,255,0.15);\n    color: #fff;\n}'
        },
        {
            label: 'Primary Buttons',
            desc: 'Main action button color',
            css: '.btn--primary,\na.btn--primary {\n    background-color: #5D63F1;\n    border-color: #5D63F1;\n}'
        },
        {
            label: 'Top Header Bar',
            desc: 'Main header background',
            css: '.ee-main-header {\n    background-color: #1a1a2e;\n    border-bottom-color: #1a1a2e;\n}'
        },
        {
            label: 'Login Page Background',
            desc: 'Login screen background color',
            css: '.login {\n    background-color: #1a1a2e;\n}\n\n.login__logo svg path,\n.login__logo svg polygon {\n    fill: #ffffff;\n}'
        },
        {
            label: 'Panel Headers',
            desc: 'Content panel header style',
            css: '.panel-heading {\n    background-color: #f4f4f8;\n    border-bottom: 2px solid #5D63F1;\n}'
        },
        {
            label: 'Links Color',
            desc: 'Global link color in CP',
            css: '.panel-body a,\n.ee-main a {\n    color: #5D63F1;\n}'
        },
        {
            label: 'Hide Footer',
            desc: 'Remove EE footer from CP',
            css: '.ee-footer {\n    display: none;\n}'
        }
    ];

    var container = document.getElementById('cs-snippets');
    var modal = document.getElementById('cs-modal');
    var textarea = document.getElementById('cs-css-textarea');

    snippets.forEach(function(s) {
        var card = document.createElement('div');
        card.style.cssText = 'border:1px solid #ddd; border-radius:4px; padding:14px; cursor:pointer; transition:all 0.15s;';
        card.innerHTML = '<div style="font-weight:600; font-size:14px; margin-bottom:4px;">' + s.label + '</div>' +
                         '<div style="color:#888; font-size:12px;">' + s.desc + '</div>';
        card.addEventListener('mouseenter', function() { card.style.borderColor = '#5D63F1'; card.style.background = '#f8f8ff'; });
        card.addEventListener('mouseleave', function() { card.style.borderColor = '#ddd'; card.style.background = ''; });
        card.addEventListener('click', function() {
            var current = textarea.value.trim();
            var addition = '\n/* ' + s.label + ' */\n' + s.css + '\n';
            textarea.value = current ? current + '\n' + addition : addition.trim();
            modal.style.display = 'none';
            textarea.focus();
            textarea.scrollTop = textarea.scrollHeight;
        });
        container.appendChild(card);
    });

    document.getElementById('cs-snippets-btn').addEventListener('click', function(e) {
        e.preventDefault();
        modal.style.display = '';
    });

    document.getElementById('cs-modal-close').addEventListener('click', function(e) {
        e.preventDefault();
        modal.style.display = 'none';
    });

    document.getElementById('cs-modal-backdrop').addEventListener('click', function() {
        modal.style.display = 'none';
    });
})();
</script>

<?php echo form_close(); ?>
