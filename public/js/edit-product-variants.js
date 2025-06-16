// Edit Product Variants JavaScript - Standalone version
console.log('Loading edit-product-variants.js...');

// Price formatting functions
function formatPrice(price) {
    if (!price || price === 0) return '';
    return parseInt(price).toLocaleString('vi-VN');
}

function unformatPrice(priceString) {
    if (!priceString) return '';
    return priceString.replace(/[^\d]/g, '');
}

// Debug functions
window.debugEditForm = function() {
    console.log('=== DEBUGGING EDIT FORM ===');
    
    const elements = {
        hasVariantsCheckbox: document.getElementById('has_variants'),
        generateVariantsBtn: document.getElementById('generate-variants'),
        variantsTableContainer: document.getElementById('variants-table-container'),
        variantsSection: document.getElementById('variants-section'),
        simpleProductSection: document.getElementById('simple-product-section')
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

// Generate variants table function
window.generateVariantsTable = function(sizes, colors) {
    console.log('generateVariantsTable called with:', { sizes, colors });
    
    let tableHTML = `
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="bulk-actions" style="display: none;">
                <button type="button" class="btn btn-outline-danger btn-sm" id="delete-selected-variants">
                    <i class="fas fa-trash me-2"></i>Xóa đã chọn (<span id="selected-count">0</span>)
                </button>
            </div>
            <div class="text-muted">
                <small>Tổng <span id="variants-count"></span> biến thể</small>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered" id="variants-table">
                <thead class="table-light">
                    <tr>
                        <th width="40">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="select-all-variants">
                                <label class="form-check-label" for="select-all-variants">
                                    <span class="visually-hidden">Chọn tất cả</span>
                                </label>
                            </div>
                        </th>
                        <th>Size</th>
                        <th>Màu sắc</th>
                        <th>Giá bán (₫)</th>
                        <th>Giá sale (₫)</th>
                        <th>Tồn kho</th>
                        <th>SKU</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
    `;

    let variantIndex = 0;
    const basePrice = document.getElementById('price') ? document.getElementById('price').value.replace(/[^\d]/g, '') : '0';
    const productSKU = document.getElementById('sku').value || 'PROD';

    // Keep existing variants first
    const existingTable = document.querySelector('#variants-table tbody');
    if (existingTable) {
        const existingRows = existingTable.querySelectorAll('tr');
        existingRows.forEach(row => {
            // Update the index in form names
            row.querySelectorAll('input, select').forEach(input => {
                if (input.name && input.name.includes('[')) {
                    input.name = input.name.replace(/\[\d+\]/, `[${variantIndex}]`);
                }
            });
            tableHTML += row.outerHTML;
            variantIndex++;
        });
    }

    // Add new variants
    sizes.forEach(size => {
        colors.forEach(color => {
            // Check if this combination already exists
            const exists = existingTable && Array.from(existingTable.querySelectorAll('tr')).some(row => {
                const sizeInput = row.querySelector('input[name*="[size]"]');
                const colorInput = row.querySelector('input[name*="[color]"]');
                return sizeInput && colorInput && 
                       sizeInput.value === size && colorInput.value === color.name;
            });

            if (!exists) {
                console.log(`Adding new variant: ${size} - ${color.name}`);
                const variantSKU = `${productSKU}-${size.toUpperCase()}-${color.name.toUpperCase().replace(/\s+/g, '')}`;
                const formattedPrice = basePrice ? parseInt(basePrice).toLocaleString('vi-VN') : '0';
                
                tableHTML += `
                    <tr>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input variant-checkbox" type="checkbox">
                            </div>
                        </td>
                        <td>
                            <input type="text" class="form-control form-control-sm" name="variants[${variantIndex}][size]" value="${size}" required>
                        </td>
                        <td>
                            <input type="text" class="form-control form-control-sm" name="variants[${variantIndex}][color]" value="${color.name}" required>
                            <input type="hidden" name="variants[${variantIndex}][color_code]" value="${color.code}">
                            <div class="d-flex align-items-center mt-1">
                                <span class="color-preview me-2" style="width: 16px; height: 16px; border-radius: 50%; background-color: ${color.code}; border: 1px solid #ddd;"></span>
                                <small class="text-muted">${color.name}</small>
                            </div>
                        </td>
                        <td>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control price-input" name="variants[${variantIndex}][price]" value="${formattedPrice}" required>
                                <span class="input-group-text">₫</span>
                            </div>
                        </td>
                        <td>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control price-input" name="variants[${variantIndex}][sale_price]" value="" placeholder="Tùy chọn">
                                <span class="input-group-text">₫</span>
                            </div>
                        </td>
                        <td>
                            <input type="number" class="form-control form-control-sm" name="variants[${variantIndex}][stock_quantity]" value="0" min="0" required>
                        </td>
                        <td>
                            <input type="text" class="form-control form-control-sm" name="variants[${variantIndex}][sku]" value="${variantSKU}" required>
                        </td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="variants[${variantIndex}][is_active]" value="1" checked>
                            </div>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-outline-danger remove-variant">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
                variantIndex++;
            }
        });
    });

    tableHTML += `
                </tbody>
            </table>
        </div>
    `;

    const variantsTableContainer = document.getElementById('variants-table-container');
    if (variantsTableContainer) {
        variantsTableContainer.innerHTML = tableHTML;
        console.log('Variants table updated, total variants:', variantIndex);
        
        // Update variants count
        const variantsCount = document.getElementById('variants-count');
        if (variantsCount) {
            variantsCount.textContent = variantIndex;
        }

        // Add basic remove listeners
        document.querySelectorAll('.remove-variant').forEach(button => {
            button.addEventListener('click', function() {
                if (confirm('Bạn có chắc chắn muốn xóa biến thể này?')) {
                    this.closest('tr').remove();
                    console.log('Variant removed');
                }
            });
        });
        
    } else {
        console.error('variants-table-container not found!');
    }
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('Edit product variants script initialized');
    
    // Setup has_variants toggle
    const hasVariantsCheckbox = document.getElementById('has_variants');
    const simpleProductSection = document.getElementById('simple-product-section');
    const variantsSection = document.getElementById('variants-section');
    
    if (hasVariantsCheckbox) {
        hasVariantsCheckbox.addEventListener('change', function() {
            if (this.checked) {
                if (simpleProductSection) simpleProductSection.style.display = 'none';
                if (variantsSection) variantsSection.style.display = 'block';
                console.log('Switched to variants mode');
            } else {
                if (simpleProductSection) simpleProductSection.style.display = 'block';
                if (variantsSection) variantsSection.style.display = 'none';
                console.log('Switched to simple mode');
            }
        });
    }
    
    // Setup generate variants button
    const generateVariantsBtn = document.getElementById('generate-variants');
    if (generateVariantsBtn) {
        generateVariantsBtn.addEventListener('click', function() {
            console.log('Generate variants button clicked!');
            
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

            generateVariantsTable(selectedSizes, selectedColors);
        });
    }
    
    // Auto-announce functions are ready
    setTimeout(() => {
        console.log('=== EDIT FORM DEBUG FUNCTIONS READY ===');
        console.log('Available functions:');
        console.log('- debugEditForm()');
        console.log('- testEditVariantGeneration()');
        console.log('- generateVariantsTable(sizes, colors)');
    }, 1000);
});

console.log('Edit product variants script loaded successfully!');
