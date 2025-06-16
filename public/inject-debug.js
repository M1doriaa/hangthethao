// Debug Edit Form - Standalone Script
// Copy and paste this entire script into the browser console

console.log('=== INJECTING DEBUG FUNCTIONS FOR EDIT FORM ===');

// Define debug function directly in console
window.debugEditForm = function() {
    console.log('=== DEBUGGING EDIT FORM ===');
    
    const elements = {
        hasVariantsCheckbox: document.getElementById('has_variants'),
        generateVariantsBtn: document.getElementById('generate-variants'),
        variantsTableContainer: document.getElementById('variants-table-container'),
        variantsSection: document.getElementById('variants-section'),
        simpleProductSection: document.getElementById('simple-product-section'),
        form: document.querySelector('form'),
        nameInput: document.getElementById('name'),
        skuInput: document.getElementById('sku'),
        priceInput: document.getElementById('price')
    };
    
    console.log('Elements found:');
    Object.entries(elements).forEach(([key, element]) => {
        console.log(`  ${key}:`, !!element, element ? element.tagName : 'NOT FOUND');
    });
    
    // Check checkboxes
    const sizeCheckboxes = document.querySelectorAll('.variant-size');
    const colorCheckboxes = document.querySelectorAll('.variant-color');
    console.log('Variant options:');
    console.log(`  Sizes: ${sizeCheckboxes.length}`);
    console.log(`  Colors: ${colorCheckboxes.length}`);
    
    // Check existing variants
    const existingVariants = document.querySelectorAll('#variants-table tbody tr');
    console.log(`Existing variants: ${existingVariants.length}`);
    
    // Check if has_variants is checked
    const hasVariants = elements.hasVariantsCheckbox;
    if (hasVariants) {
        console.log(`Has variants checked: ${hasVariants.checked}`);
    }
    
    return elements;
};

window.testEditVariantGeneration = function() {
    console.log('=== TESTING VARIANT GENERATION IN EDIT ===');
    
    // Check if variants section is visible
    const hasVariants = document.getElementById('has_variants');
    if (hasVariants && !hasVariants.checked) {
        console.log('Enabling has_variants...');
        hasVariants.checked = true;
        hasVariants.dispatchEvent(new Event('change'));
    }
    
    // Select some sizes and colors
    const firstSize = document.querySelector('.variant-size');
    const secondSize = document.querySelectorAll('.variant-size')[1];
    const firstColor = document.querySelector('.variant-color');
    const secondColor = document.querySelectorAll('.variant-color')[1];
    
    if (firstSize) {
        firstSize.checked = true;
        console.log('Selected size:', firstSize.value);
    }
    if (secondSize) {
        secondSize.checked = true;
        console.log('Selected size:', secondSize.value);
    }
    if (firstColor) {
        firstColor.checked = true;
        console.log('Selected color:', firstColor.value);
    }
    if (secondColor) {
        secondColor.checked = true;
        console.log('Selected color:', secondColor.value);
    }
    
    // Click generate button
    setTimeout(() => {
        const generateBtn = document.getElementById('generate-variants');
        if (generateBtn) {
            console.log('Clicking generate variants button...');
            generateBtn.click();
        } else {
            console.error('Generate variants button not found!');
        }
    }, 1000);
};

window.testGenerateButtonDirectly = function() {
    console.log('=== TESTING GENERATE BUTTON DIRECTLY ===');
    
    const generateBtn = document.getElementById('generate-variants');
    if (generateBtn) {
        console.log('Generate button found, testing click...');
        
        // Check if button has event listener
        const listeners = getEventListeners ? getEventListeners(generateBtn) : 'Cannot check listeners';
        console.log('Button listeners:', listeners);
        
        // Simulate selections
        const firstSize = document.querySelector('.variant-size');
        const firstColor = document.querySelector('.variant-color');
        
        if (firstSize) firstSize.checked = true;
        if (firstColor) firstColor.checked = true;
        
        // Click button
        generateBtn.click();
        console.log('Button clicked!');
    } else {
        console.error('Generate button not found!');
    }
};

window.fixGenerateButton = function() {
    console.log('=== FIXING GENERATE BUTTON ===');
    
    const generateBtn = document.getElementById('generate-variants');
    if (!generateBtn) {
        console.error('Generate button not found!');
        return;
    }
    
    // Remove existing listeners and add new one
    const newBtn = generateBtn.cloneNode(true);
    generateBtn.parentNode.replaceChild(newBtn, generateBtn);
    
    // Add event listener manually
    newBtn.addEventListener('click', function() {
        console.log('Manual generate button clicked!');
        
        const selectedSizes = Array.from(document.querySelectorAll('.variant-size:checked')).map(cb => cb.value);
        const selectedColors = Array.from(document.querySelectorAll('.variant-color:checked')).map(cb => ({
            name: cb.value,
            code: cb.dataset.colorCode
        }));

        console.log('Selected sizes:', selectedSizes);
        console.log('Selected colors:', selectedColors);

        if (selectedSizes.length === 0 && selectedColors.length === 0) {
            alert('Vui lòng chọn ít nhất 1 size hoặc 1 màu sắc để tạo biến thể.');
            return;
        }

        // If only sizes selected, use default color
        if (selectedSizes.length > 0 && selectedColors.length === 0) {
            selectedColors.push({ name: 'Mặc định', code: '#ffffff' });
        }
        
        // If only colors selected, use default size
        if (selectedColors.length > 0 && selectedSizes.length === 0) {
            selectedSizes.push('OneSize');
        }

        // Call generate function
        if (window.manualGenerateVariantsTable) {
            window.manualGenerateVariantsTable(selectedSizes, selectedColors);
        } else {
            console.error('manualGenerateVariantsTable function not available');
            // Create simple variants table
            createSimpleVariantsTable(selectedSizes, selectedColors);
        }
    });
    
    console.log('Generate button fixed with new event listener');
};

function createSimpleVariantsTable(sizes, colors) {
    console.log('Creating simple variants table...');
    
    const container = document.getElementById('variants-table-container');
    if (!container) {
        console.error('Variants table container not found!');
        return;
    }
    
    let html = `
        <div class="table-responsive">
            <table class="table table-bordered" id="variants-table">
                <thead class="table-light">
                    <tr>
                        <th>Size</th>
                        <th>Màu sắc</th>
                        <th>Giá bán (₫)</th>
                        <th>Tồn kho</th>
                        <th>SKU</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
    `;
    
    let index = 0;
    const basePrice = document.getElementById('price') ? document.getElementById('price').value.replace(/[^\d]/g, '') : '500000';
    const baseSku = document.getElementById('sku') ? document.getElementById('sku').value : 'PROD';
    
    sizes.forEach(size => {
        colors.forEach(color => {
            const variantSku = `${baseSku}-${size}-${color.name.replace(/\s+/g, '')}`;
            html += `
                <tr>
                    <td>
                        <input type="text" class="form-control form-control-sm" name="variants[${index}][size]" value="${size}" required>
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm" name="variants[${index}][color]" value="${color.name}" required>
                        <input type="hidden" name="variants[${index}][color_code]" value="${color.code}">
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm" name="variants[${index}][price]" value="${basePrice}" required>
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm" name="variants[${index}][stock_quantity]" value="0" min="0" required>
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm" name="variants[${index}][sku]" value="${variantSku}" required>
                    </td>
                    <td>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="variants[${index}][is_active]" value="1" checked>
                        </div>
                    </td>
                </tr>
            `;
            index++;
        });
    });
    
    html += '</tbody></table></div>';
    container.innerHTML = html;
    console.log(`Created ${index} variant rows`);
}

console.log('✅ Debug functions injected successfully!');
console.log('Available functions:');
console.log('- debugEditForm()');
console.log('- testEditVariantGeneration()');
console.log('- testGenerateButtonDirectly()');
console.log('- fixGenerateButton()');
console.log('');
console.log('Start with: debugEditForm()');
