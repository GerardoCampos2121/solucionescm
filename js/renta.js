/**
 * SECM Rent a Car - Customer Validation and Booking Form Script
 */

$(document).ready(function() {
    // Store registered customer data
    let registeredCustomerData = null;
    
    // Check if customer verification section exists
    if ($('#customer-check-section').length === 0) {
        return; // Not on booking form page
    }
    
    /**
     * Check if customer exists in database
     */
    $('#btnCheckCustomer').on('click', function() {
        const documentId = $('#check_document_id').val().trim();
        // Validate input
        if (!documentId) {
            showMessage('Please enter a Document ID to check.', 'danger');
            return;
        }

        // Add loading state
        $(this).addClass('loading').prop('disabled', true);
        hideMessage();
        hideActions();

        // Make AJAX request
        $.ajax({
            url: 'api/check_customer.php',
            type: 'POST',
            data: {
                document_id: documentId
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    if (response.exists) {
                        // Customer found - Auto-fill the form immediately
                        registeredCustomerData = response.customer;

                        // Fill form with customer data
                        fillFormWithData(response.customer);

                        // Update the document_id in the main form
                        $('#document_id').val(response.customer.numero_documento);

                        // Disable form fields (customer is verified)
                        disableForm();

                        showMessage('Customer verified: ' + response.customer.nombre + '. Your information has been loaded.', 'success');

                        // Show only the "Enter New Information" button in case they want to edit
                        showEnterNewButton();
                    } else {
                        // Customer not found
                        registeredCustomerData = null;
                        showMessage(response.message + ' Please complete your information below.', 'info');
                        hideActions();

                        // Enable form for new registration
                        enableForm();

                        // Focus on name field
                        setTimeout(function() {
                            $('#name').focus();
                        }, 500);
                    }
                } else {
                    showMessage('Error: ' + response.message, 'danger');
                    hideActions();
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                console.error('Response:', xhr.responseText);
                showMessage('An error occurred while checking. Please try again.', 'danger');
                hideActions();
            },
            complete: function() {
                // Remove loading state
                $('#btnCheckCustomer').removeClass('loading').prop('disabled', false);
            }
        });
    });
    
    /**
     * Use registered customer data to fill the form
     */
    $('#btnUseRegisteredData').on('click', function() {
        if (!registeredCustomerData) {
            showMessage('No customer data available.', 'danger');
            return;
        }
        
        // Fill form with registered customer data
        fillFormWithData(registeredCustomerData);
        
        // Disable form fields (customer is verified)
        disableForm();
        
        // Update the document_id field
        $('#document_id').val(registeredCustomerData.numero_documento);
        
        // Hide the customer check section (optional - can be kept for re-verification)
        // $('#customer-check-section').slideUp();
        
        showMessage('Information loaded successfully. Please review and confirm your booking.', 'success');
        
        // Hide action buttons
        hideActions();
    });
    
    /**
     * Allow user to enter new information even if customer exists
     */
    $('#btnEnterNewData').on('click', function() {
        registeredCustomerData = null;
        
        // Clear the message
        hideMessage();
        hideActions();
        
        // Enable form for new data entry
        enableForm();
        
        // Focus on name field
        $('#name').focus();
    });
    
    /**
     * Allow pressing Enter key to trigger check
     */
    $('#check_document_id').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#btnCheckCustomer').click();
        }
    });
    
    /**
     * Show message in the alert box
     */
    function showMessage(text, type) {
        const messageDiv = $('#customer-check-message');
        const messageText = $('#customer-check-text');
        const alertDiv = messageDiv.find('.alert');
        
        // Remove all alert classes
        alertDiv.removeClass('alert-success alert-danger alert-info alert-warning');
        
        // Add the appropriate class
        alertDiv.addClass('alert-' + type);
        
        // Set the message
        messageText.text(text);
        
        // Show the message
        messageDiv.fadeIn(300);
    }
    
    /**
     * Hide the message
     */
    function hideMessage() {
        $('#customer-check-message').fadeOut(200);
    }
    
    /**
     * Show action buttons (Use My Info / Enter New)
     */
    function showActions() {
        $('#customer-found-actions').fadeIn(300);
    }
    
    /**
     * Hide action buttons
     */
    function hideActions() {
        $('#customer-found-actions').fadeOut(200);
    }
    
    /**
     * Show only the "Enter New Information" button
     * Used when customer is found and form is auto-filled
     */
    function showEnterNewButton() {
        // Hide the full action buttons div
        hideActions();
        
        // Show a simplified message with option to edit
        const editBtnHtml = `
            <div id="customer-found-actions" class="mt-3" style="display: block;">
                <button type="button" class="btn btn-outline-secondary btn-sm" id="btnEnterNewData">
                    <i class="fas fa-pen me-1"></i>Edit Information
                </button>
            </div>
        `;
        
        // Remove existing edit button if any
        $('#customer-found-actions').remove();
        
        // Add the edit button after the message
        $('#customer-check-message').after(editBtnHtml);
        
        // Re-bind the click event
        $('#btnEnterNewData').on('click', function() {
            registeredCustomerData = null;
            hideMessage();
            $(this).parent().remove();
            enableForm();
            $('#name').focus();
        });
    }
    
    /**
     * Fill form with customer data
     */
    function fillFormWithData(customer) {
        $('#name').val(customer.nombre || '');
        $('#document_id').val(customer.numero_documento || '');
        $('#age').val(customer.edad || '');
        $('#address').val(customer.direccion || '');
        $('#email').val(customer.correo || '');
        $('#phone').val(customer.contacto || '');
    }
    
    /**
     * Disable form fields (for verified customers)
     */
    function disableForm() {
        const form = $('#bookingForm');
        //form.addClass('form-disabled');               
        form.find('input, textarea').prop('readonly', true);

        
    }
    
    /**
     * Enable form fields (for new customers)
     */
    function enableForm() {
        const form = $('#bookingForm');
        form.removeClass('form-disabled');
        form.find('input, textarea').prop('disabled', false);
    }
    
    /**
     * Form validation before submission
     */
    $('#bookingForm').on('submit', function(e) {
        const requiredFields = ['name', 'document_id', 'age', 'address', 'email', 'phone'];
        let isValid = true;
        
        requiredFields.forEach(function(fieldId) {
            const field = $('#' + fieldId);
            const value = field.val().trim();
            
            if (!value) {
                isValid = false;
                field.addClass('is-invalid');
            } else {
                field.removeClass('is-invalid');
            }
        });
        
        // Email validation
        const email = $('#email').val().trim();
        if (email && !isValidEmail(email)) {
            isValid = false;
            $('#email').addClass('is-invalid');
        }
        
        if (!isValid) {
            e.preventDefault();
            showMessage('Please fill in all required fields correctly.', 'danger');
            return false;
        }
        
        return true;
    });
    
    /**
     * Remove invalid class on input
     */
    $('#bookingForm').on('input', 'input, textarea', function() {
        $(this).removeClass('is-invalid');
    });
    
    /**
     * Validate email format
     */
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
});