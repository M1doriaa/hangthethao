// Debug form creation issues
window.debugProductForm = function() {
    console.log('=== DEBUGGING PRODUCT FORM ===');
    
    // Check if elements exist
    const elements = {
        form: document.querySelector('form'),
        hasVariantsCheckbox: document.getElementById('has_variants'),
        generateBtn: document.getElementById('generate-variants-btn'),
        variantsTable: document.getElementById('variants-table-body'),
        nameInput: document.getElementById('name'),
        skuInput: document.getElementById('sku'),
        priceInput: document.getElementById('price'),
        mainImageInput: document.getElementById('main_image')
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
    
    // Check if any variants exist
    const variantRows = document.querySelectorAll('#variants-table-body tr');
    console.log(`Variant rows: ${variantRows.length}`);
    
    return elements;
};

window.testSimpleProductCreation = function() {
    console.log('=== TESTING SIMPLE PRODUCT CREATION ===');
    
    // Fill in basic form
    const nameInput = document.getElementById('name');
    const skuInput = document.getElementById('sku');
    const priceInput = document.getElementById('price');
    const categorySelect = document.getElementById('category');
    const descriptionInput = document.getElementById('description');
    const stockInput = document.getElementById('stock_quantity');
    const statusSelect = document.getElementById('status');
    
    if (nameInput) nameInput.value = 'Test Product Simple ' + Date.now();
    if (skuInput) skuInput.value = 'TEST-SIMPLE-' + Date.now();
    if (priceInput) priceInput.value = '500,000';
    if (categorySelect && categorySelect.options.length > 0) categorySelect.value = categorySelect.options[1]?.value || categorySelect.options[0]?.value;
    if (descriptionInput) descriptionInput.value = 'Test product description for debugging';
    if (stockInput) stockInput.value = '100';
    if (statusSelect) statusSelect.value = 'active';
    
    // Make sure has_variants is unchecked
    const hasVariants = document.getElementById('has_variants');
    if (hasVariants) {
        hasVariants.checked = false;
        hasVariants.dispatchEvent(new Event('change'));
    }
    
    // Create a small test image file
    createTestImage().then(file => {
        const fileInput = document.getElementById('main_image');
        if (fileInput) {
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            fileInput.files = dataTransfer.files;
            fileInput.dispatchEvent(new Event('change'));
        }
    });
    
    console.log('Simple product form filled. You can now submit manually.');
    
    // Auto-submit after 5 seconds
    setTimeout(() => {
        if (confirm('Auto-submit the form now?')) {
            document.querySelector('form').submit();
        }
    }, 5000);
};

window.createTestImage = async function() {
    const canvas = document.createElement('canvas');
    canvas.width = 100;
    canvas.height = 100;
    const ctx = canvas.getContext('2d');
    ctx.fillStyle = '#ff0000';
    ctx.fillRect(0, 0, 100, 100);
    
    return new Promise(resolve => {
        canvas.toBlob(blob => {
            resolve(new File([blob], 'test-image.png', { type: 'image/png' }));
        }, 'image/png');
    });
};

window.testVariantProductCreation = function() {
    console.log('=== TESTING VARIANT PRODUCT CREATION ===');
    
    // Fill basic form
    const nameInput = document.getElementById('name');
    const skuInput = document.getElementById('sku');
    const priceInput = document.getElementById('price');
    const categorySelect = document.getElementById('category');
    
    if (nameInput) nameInput.value = 'Test Product Variants';
    if (skuInput) skuInput.value = 'TEST-VAR-' + Date.now();
    if (priceInput) priceInput.value = '500,000';
    if (categorySelect) categorySelect.value = categorySelect.options[1]?.value || '';
    
    // Enable variants
    const hasVariants = document.getElementById('has_variants');
    if (hasVariants) {
        hasVariants.checked = true;
        hasVariants.dispatchEvent(new Event('change'));
    }
    
    // Select some sizes and colors
    const firstSize = document.querySelector('.variant-size');
    const secondSize = document.querySelectorAll('.variant-size')[1];
    const firstColor = document.querySelector('.variant-color');
    const secondColor = document.querySelectorAll('.variant-color')[1];
    
    if (firstSize) firstSize.checked = true;
    if (secondSize) secondSize.checked = true;
    if (firstColor) firstColor.checked = true;
    if (secondColor) secondColor.checked = true;
    
    console.log('Variant product form prepared. Now click Generate Variants button.');
    
    // Auto-click generate button
    setTimeout(() => {
        const generateBtn = document.getElementById('generate-variants-btn');
        if (generateBtn) {
            console.log('Auto-clicking generate button...');
            generateBtn.click();
        }
    }, 1000);
};

// Auto-run debug on page load
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        console.log('=== DEBUG FUNCTIONS READY ===');
        console.log('Run debugProductForm() to check form elements');
        console.log('Run testSimpleProductCreation() to test simple product');
        console.log('Run testVariantProductCreation() to test variant product');
        
        // Auto debug
        debugProductForm();
    }, 2000);
});
