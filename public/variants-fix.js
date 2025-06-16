// Quick fix for variants - simple version without bulk actions
function simpleGenerateVariants() {
    console.log('=== SIMPLE VARIANTS GENERATION ===');
    
    const selectedSizes = Array.from(document.querySelectorAll('.variant-size:checked')).map(cb => cb.value);
    const selectedColors = Array.from(document.querySelectorAll('.variant-color:checked')).map(cb => ({
        name: cb.value,
        code: cb.dataset.colorCode
    }));

    console.log('Selected sizes:', selectedSizes);
    console.log('Selected colors:', selectedColors);

    if (selectedSizes.length === 0 && selectedColors.length === 0) {
        alert('Vui lòng chọn ít nhất một size hoặc màu sắc!');
        return;
    }

    // Generate combinations
    const variants = [];
    
    if (selectedSizes.length > 0 && selectedColors.length > 0) {
        selectedSizes.forEach(size => {
            selectedColors.forEach(color => {
                variants.push({
                    size: size,
                    color: color.name,
                    colorCode: color.code
                });
            });
        });
    } else if (selectedSizes.length > 0) {
        selectedSizes.forEach(size => {
            variants.push({
                size: size,
                color: '',
                colorCode: ''
            });
        });
    } else {
        selectedColors.forEach(color => {
            variants.push({
                size: '',
                color: color.name,
                colorCode: color.code
            });
        });
    }

    console.log('Generated variants:', variants);

    // Simple table generation
    const variantsTableBody = document.getElementById('variants-table-body');
    const variantsTableContainer = document.getElementById('variants-table-container');
    
    if (!variantsTableBody) {
        console.error('Table body not found');
        return;
    }
    
    const priceInput = document.getElementById('price');
    const skuInput = document.getElementById('sku');
    const basePrice = priceInput ? priceInput.value.replace(/[^\d]/g, '') || 0 : 0;
    const baseSku = skuInput ? skuInput.value || '' : '';
    
    variantsTableBody.innerHTML = '';
    
    variants.forEach((variant, index) => {
        const row = document.createElement('tr');
        const variantSku = baseSku + (variant.size ? `-${variant.size}` : '') + (variant.color ? `-${variant.color.replace(/\s+/g, '')}` : '');
        
        row.innerHTML = `
            <td>
                <input type="checkbox" class="form-check-input">
            </td>
            <td>
                <input type="hidden" name="variants[${index}][size]" value="${variant.size || ''}">
                ${variant.size || '-'}
            </td>
            <td>
                <input type="hidden" name="variants[${index}][color]" value="${variant.color || ''}">
                <input type="hidden" name="variants[${index}][color_code]" value="${variant.colorCode || ''}">
                <div class="d-flex align-items-center">
                    ${variant.colorCode ? `<span style="width: 20px; height: 20px; border-radius: 50%; background-color: ${variant.colorCode}; border: 1px solid #ddd; display: inline-block; margin-right: 8px;"></span>` : ''}
                    ${variant.color || '-'}
                </div>
            </td>
            <td>
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" name="variants[${index}][price]" 
                           value="${parseInt(basePrice).toLocaleString('vi-VN')}" required>
                    <span class="input-group-text">₫</span>
                </div>
            </td>
            <td>
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" name="variants[${index}][sale_price]" 
                           placeholder="Tùy chọn">
                    <span class="input-group-text">₫</span>
                </div>
            </td>
            <td>
                <input type="number" class="form-control form-control-sm" name="variants[${index}][stock_quantity]" 
                       value="0" min="0" required>
            </td>
            <td>
                <input type="text" class="form-control form-control-sm" name="variants[${index}][sku]" 
                       value="${variantSku}" required>
            </td>
            <td>
                <input type="checkbox" name="variants[${index}][is_active]" value="1" checked>
            </td>
            <td>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('tr').remove()">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
        
        variantsTableBody.appendChild(row);
    });
    
    variantsTableContainer.style.display = 'block';
    console.log('Variants table generated successfully!');
}

// Test function
window.testSimpleVariants = function() {
    console.log('Testing simple variants...');
    
    // Enable variants mode
    const hasVariantsCheckbox = document.getElementById('has_variants');
    if (hasVariantsCheckbox) {
        hasVariantsCheckbox.checked = true;
        hasVariantsCheckbox.dispatchEvent(new Event('change'));
    }
    
    // Select test data
    const sizeS = document.getElementById('size_S');
    const sizeM = document.getElementById('size_M');
    if (sizeS) sizeS.checked = true;
    if (sizeM) sizeM.checked = true;
    
    const colorRed = document.querySelector('.variant-color[value="Đỏ"]');
    if (colorRed) colorRed.checked = true;
    
    const priceInput = document.getElementById('price');
    if (priceInput) priceInput.value = '500,000';
    
    // Generate
    simpleGenerateVariants();
};

console.log('Simple variants fix loaded. Run "testSimpleVariants()" to test.');
