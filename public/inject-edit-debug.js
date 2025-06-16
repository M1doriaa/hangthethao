// Debug functions for edit product form - inject this via browser console

// Override console to help with debugging
const originalLog = console.log;
console.log = function(...args) {
    originalLog.apply(console, arguments);
    // Also try to show in UI if possible
    try {
        const debugElement = document.getElementById('debug-output');
        if (debugElement) {
            debugElement.innerHTML += '<div>' + args.join(' ') + '</div>';
        }
    } catch(e) {
        // Ignore UI logging errors
    }
};

// Debug function for edit form
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

// Quick test of generate variants functionality
window.testGenerateVariants = function() {
    console.log('=== TESTING GENERATE VARIANTS FUNCTION ===');
    
    if (typeof generateVariantsTable === 'function') {
        console.log('generateVariantsTable function found');
        try {
            generateVariantsTable();
            console.log('generateVariantsTable executed successfully');
        } catch(error) {
            console.error('Error in generateVariantsTable:', error);
        }
    } else {
        console.error('generateVariantsTable function not found');
    }
};

// Check if all required functions are available
window.checkEditFormFunctions = function() {
    console.log('=== CHECKING EDIT FORM FUNCTIONS ===');
    
    const functions = [
        'generateVariantsTable',
        'formatPrice',
        'unformatPrice',
        'debugEditForm',
        'testEditVariantGeneration'
    ];
    
    functions.forEach(funcName => {
        const exists = typeof window[funcName] === 'function';
        console.log(`${funcName}:`, exists ? 'AVAILABLE' : 'NOT FOUND');
    });
};

console.log('=== EDIT FORM DEBUG FUNCTIONS INJECTED ===');
console.log('Available functions:');
console.log('- debugEditForm() - Check form elements');
console.log('- testEditVariantGeneration() - Test variant generation');
console.log('- testGenerateVariants() - Test generate function directly');
console.log('- checkEditFormFunctions() - Check what functions are available');

// Auto-run basic checks
setTimeout(() => {
    checkEditFormFunctions();
    debugEditForm();
}, 1000);
