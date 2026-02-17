document.addEventListener("DOMContentLoaded", function() {
  const tabContents = document.querySelectorAll('.tabcontent');
  const tabLinks = document.querySelectorAll('.tablinks');
  if (tabContents.length > 0 && tabLinks.length > 0) {
    tabContents[0].style.display = "block";
    tabLinks[0].classList.add("active");
  }

  function unq(s){
    if (s == null) return "";
    s = String(s).replace(/&quot;/g, '"').trim();
    if (s[0] === '"' && s[s.length-1] === '"') s = s.slice(1,-1);
    return s;
  }
  function parseMaybeJSON(raw){
    if (raw == null || raw === "") return "";
    const str = unq(raw);
    try { return JSON.parse(str); } catch(e) { return str; }
  }
  function readControlValue(fieldId){
    try {
      var maybe = JSON.parse(String(fieldId).replace(/&quot;/g,'"'));
      if (Array.isArray(maybe) && maybe.length >= 1 && typeof maybe[0] === 'string') fieldId = maybe[0];
    } catch(e){}
    var radio = document.querySelector('input[name="'+fieldId+'"]:checked');
    if (radio) return String(radio.value);
    var el = document.getElementById(fieldId);
    if (!el) return "";
    if (el.type === "checkbox") return el.checked;
    return (el.value != null) ? String(el.value) : "";
  }
  function eqLike(a,b){
    if (typeof b === "boolean") return (typeof a === "boolean" ? a : (String(a)==="true")) === b;
    return String(a) === String(b);
  }
  function neqLike(a,b){
    if (typeof b === "boolean") return !eqLike(a,b);
    return String(a) !== String(b);
  }
  function matchCondition(fieldId, opRaw, reqRaw){
    let op = unq(opRaw || "=").toLowerCase();
    if (op === "==") op = "=";
    if (op === "in") op = "or";
    const cur = readControlValue(fieldId);
    const req = parseMaybeJSON(reqRaw);
    if (op === "=")  return eqLike(cur, req);
    if (op === "!=") return neqLike(cur, req);
    if (op === "or") {
      const arr = Array.isArray(req) ? req : [req];
      return arr.map(String).includes(String(cur));
    }
    return eqLike(cur, req);
  }
  function getRequireTriples(box){
    const jr = box.getAttribute('data-require');
    if (jr) {
      let parsed = parseMaybeJSON(jr);
      if (Array.isArray(parsed) && Array.isArray(parsed[0])) {
        return parsed.map(function(r){ return [String(r[0]||''), String((r[1]||'=')).toLowerCase(), (typeof r[2]==='string'? r[2] : JSON.stringify(r[2]))]; });
      }
      if (Array.isArray(parsed)) {
        return [[String(parsed[0]||''), String((parsed[1]||'=')).toLowerCase(), (typeof parsed[2]==='string'? parsed[2] : JSON.stringify(parsed[2]))]];
      }
      if (parsed && parsed.rules && Array.isArray(parsed.rules)) {
        return parsed.rules.map(function(r){ return [String(r[0]||''), String((r[1]||'=')).toLowerCase(), (typeof r[2]==='string'? r[2] : JSON.stringify(r[2]))]; });
      }
    }
    var r0 = box.getAttribute('data-require-0');
    if (!r0) return [];
    var parsed0 = null;
    try { parsed0 = JSON.parse(String(r0).replace(/&quot;/g, '"')); } catch(e){}
    if (Array.isArray(parsed0)) {
      var f = String(parsed0[0] || '');
      var op = String((parsed0[1] || '=')).toLowerCase();
      var v  = parsed0[2];
      var vRaw = (typeof v === 'string') ? v : JSON.stringify(v);
      return [[f, op, vRaw]];
    }
    var opAttr = box.getAttribute('data-require-1') || '"="';
    var vAttr  = box.getAttribute('data-require-2') || '""';
    var f2  = String(r0).replace(/&quot;/g, '"').trim();
    if (f2[0] === '"' && f2[f2.length - 1] === '"') f2 = f2.slice(1, -1);
    var op2 = String(opAttr).replace(/&quot;/g, '"').trim();
    if (op2[0] === '"' && op2[op2.length - 1] === '"') op2 = op2.slice(1, -1);
    return [[f2, op2, vAttr]];
  }
  function updateFieldVisibility(box){
    const triples = getRequireTriples(box);
    if (!triples.length) return;
    let visible = true;
    for (let i=0;i<triples.length;i++){
      const t = triples[i];
      if (!matchCondition(t[0], t[1], t[2])) { visible = false; break; }
    }
    box.setAttribute('display', visible ? 'true' : 'false');
    box.style.display = visible ? 'flex' : 'none';
  }
  function applyConditions(){
    const boxes = document.querySelectorAll('.optionino-conditional-option[data-require], .optionino-conditional-option[data-require-0]');
    if (!boxes.length) return;
    boxes.forEach(updateFieldVisibility);
  }

  const OPTNNO = document.getElementById('optionino');
  const form = document.getElementById('save-options-optionino');
  if (form) {
    const errorText = form.querySelector('.error-text');
    const successText = form.querySelector('.success-text');
    form.addEventListener('submit', function(event) {
      event.preventDefault();
      if (OPTNNO) OPTNNO.classList.add('loading');
      if (errorText) errorText.style.display = 'none';
      if (successText) successText.style.display = 'none';
      let formData = new FormData(form);
      const checkboxes = form.querySelectorAll('input[type="checkbox"]');
      checkboxes.forEach(function(checkbox){
        const checkboxName = checkbox.name;
        if (!formData.has(checkboxName)) formData.set(checkboxName, 'off');
      });
      const repeaterFields = document.querySelectorAll('.optionino-repeater-field[data-repeater-name]');
      repeaterFields.forEach(function(repeaterField){
        const repeaterName = repeaterField.getAttribute('data-repeater-name');
        const repeaterValues = collectRepeaterValues(repeaterField);
        formData.append(repeaterName, repeaterValues);
      });
      formData.append('action', 'save_optionino_data');
      formData.append('security', (window.data_optionino||{}).nonce);
      const xhr = new XMLHttpRequest();
      xhr.open('POST', (window.data_optionino||{}).ajax_url, true);
      xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
      xhr.onload = function() {
        if (OPTNNO) OPTNNO.classList.remove('loading');
        let response = {};
        try { response = JSON.parse(xhr.responseText || '{}'); } catch(e){}
        if (xhr.status === 200) {
          if (response.success) {
            if (successText) { successText.textContent = (response.data && response.data.message) || 'Saved.'; successText.style.display = 'block'; }
          } else {
            if (errorText) { errorText.textContent = (response.data && response.data.message) || 'Failed.'; errorText.style.display = 'block'; }
          }
        } else {
          if (errorText) { errorText.textContent = "An error occurred while processing your request."; errorText.style.display = 'block'; }
        }
      };
      xhr.onerror = function() {
        if (OPTNNO) OPTNNO.classList.remove('loading');
        if (errorText) { errorText.textContent = "An error occurred while processing your request."; errorText.style.display = 'block'; }
      };
      xhr.send(formData);
    });
    function collectRepeaterValues(repeaterField){
      const repeaterItems = repeaterField.querySelectorAll('.optionino-repeater-item');
      const mainArray = [];
      repeaterItems.forEach(function(repeaterItem){
        const subArray = {};
        repeaterItem.querySelectorAll('input, textarea, select').forEach(function(input){
          subArray[input.name.replace(/_\d+$/, '').replace(/^_/, '')] = input.value;
        });
        mainArray.push(encodeRepeaterValues(subArray));
      });
      return encodeRepeaterValues(mainArray);
    }
    function encodeRepeaterValues(values){
      const params = new URLSearchParams();
      for (const key in values) {
        if (Object.prototype.hasOwnProperty.call(values, key)) {
          if (Array.isArray(values[key])) {
            params.append(key, JSON.stringify(values[key]));
          } else {
            params.append(key, values[key]);
          }
        }
      }
      return params.toString();
    }
  }

  function updateAll(){ applyConditions(); }
  updateAll();
  document.addEventListener('change', updateAll, true);
  const mo = new MutationObserver(function(){ updateAll(); });
  mo.observe(document.body, {subtree:true, childList:true, attributes:true, attributeFilter:['checked','value']});

  function initColorPickers(context) {
    if (window.jQuery && jQuery.fn.wpColorPicker) {
      const $context = context ? jQuery(context) : jQuery(document);
      $context.find('.optionino-color-selector').each(function(){
        if (!jQuery(this).closest('.wp-picker-container').length) {
          jQuery(this).wpColorPicker();
        }
      });
    }
  }

  document.addEventListener('click', function(event) {
    if (event.target.closest('.optionino-add-repeater-item')) {
      event.preventDefault();
      const button = event.target.closest('.optionino-add-repeater-item');
      const field = button.closest('.optionino-repeater-field');
      if (!field) return;
      const repeaterContainer = field.querySelector('.optionino-repeater-container');
      const items = repeaterContainer.querySelectorAll('.optionino-repeater-item');
      let newItem;
      
      if (items.length > 0) {
        const template = items[0];
        const index = items.length;
        newItem = template.cloneNode(true);
        
        const pickers = newItem.querySelectorAll('.wp-picker-container');
        pickers.forEach(function(picker){
          const input = picker.querySelector('input.optionino-color-selector');
          if (input) {
            picker.parentNode.insertBefore(input, picker);
            picker.remove();
          }
        });

        newItem.querySelectorAll('input, textarea, select').forEach(function(input){
          const idParts = (input.id || '').split('_'); 
          if (!isNaN(parseInt(idParts[idParts.length-1]))) {
             idParts.pop(); 
          }
          idParts.push(index); 
          const newId = idParts.join('_');
          input.id = newId;
          
          const nameParts = (input.name || '').split('_'); 
          if (!isNaN(parseInt(nameParts[nameParts.length-1]))) {
             nameParts.pop();
          }
          nameParts.push(index); 
          input.name = nameParts.join('_');
          
          if (input.type === 'checkbox' || input.type === 'radio') { 
            input.checked = false; 
            if (input.classList.contains('optionino-switch-checkbox')) {
               const label = newItem.querySelector('label[for="' + idParts.slice(0, -1).join('_') + '_' + (index > 0 ? 0 : 0) + '"]'); 
               const siblingLabel = input.nextElementSibling;
               if (siblingLabel && siblingLabel.tagName === 'LABEL') {
                 siblingLabel.setAttribute('for', newId);
               }
            }
          } else { 
            input.value = ''; 
          }
        });
      
        newItem.querySelectorAll('label').forEach(function(label){
            const forAttr = label.getAttribute('for');
            if (forAttr) {
                const parts = forAttr.split('_');
                if (!isNaN(parseInt(parts[parts.length-1]))) {
                    parts.pop();
                    parts.push(index);
                    label.setAttribute('for', parts.join('_'));
                }
            }
        });

        newItem.querySelectorAll('.optionino-image-preview').forEach(function(img){
            const id = img.id;
            const parts = id.split('-');
            const prefix = parts[0].split('_');
            if (!isNaN(parseInt(prefix[prefix.length-1]))) {
                 prefix.pop();
            }
            prefix.push(index);
            img.id = prefix.join('_') + '-preview';
            img.src = '';
        });
        
        newItem.querySelectorAll('.upload-image-button').forEach(function(btn){
             const oldData = btn.getAttribute('data-image-field');
             if (oldData) {
                 const parts = oldData.split('_');
                 if (!isNaN(parseInt(parts[parts.length-1]))) {
                    parts.pop();
                 }
                 parts.push(index);
                 btn.setAttribute('data-image-field', parts.join('_'));
             }
        });

      } else {
        newItem = document.createElement('div');
        newItem.className = 'optionino-repeater-item';
        newItem.innerHTML = '<div>New item</div>';
      }
      repeaterContainer.appendChild(newItem);
      
      initColorPickers(newItem);
      
      updateAll();
    }
    if (event.target.classList.contains('optionino-remove-repeater-item') || event.target.closest('.optionino-remove-repeater-item')) {
      event.preventDefault();
      const button = event.target.closest('.optionino-remove-repeater-item') || event.target;
      const field = button.closest('.optionino-repeater-field');
      if (!field) return;
      const container = field.querySelector('.optionino-repeater-container');
      const items = container.querySelectorAll('.optionino-repeater-item');
      if (items.length > 1) {
        button.closest('.optionino-repeater-item').remove();
        updateAll();
      }
    }
  });

  const fileFrames = {};
  document.addEventListener('click', function(event) {
    if (event.target.classList.contains('upload-image-button')) {
      event.preventDefault();
      const button = event.target;
      const identifier = button.getAttribute('data-image-field');
      if (fileFrames[identifier]) { fileFrames[identifier].open(); return; }
      if (!window.wp || !wp.media) return;
      fileFrames[identifier] = wp.media.frames.fileFrame = wp.media({
        title: 'Choose a media to upload',
        button: { text: 'select image' },
        multiple: false
      });
      fileFrames[identifier].on('select', function() {
        const attachment = fileFrames[identifier].state().get('selection').first().toJSON();
        const imageUrlInput = document.getElementById(identifier);
        if (imageUrlInput) imageUrlInput.value = attachment.url;
        const previewImage = document.getElementById(identifier + '-preview');
        if (previewImage) previewImage.src = attachment.url;
        if (typeof wpMediaPostId !== 'undefined') wp.media.model.settings.post.id = wpMediaPostId;
      });
      fileFrames[identifier].open();
    }
  });

  initColorPickers();
});
