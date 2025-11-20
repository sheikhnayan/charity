{{-- Investor Information Modal --}}
<div class="modal" id="investorInfoModal" tabindex="-1" aria-labelledby="investorInfoModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="investorInfoModalLabel">Complete Your Investor Profile</h5>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-4">Please provide your investor information to proceed with your purchase.</p>
                
                <form id="investor-profile-form">
                    @csrf
                    <div class="mb-3">
                        <label for="modal_investor_type" class="form-label">Pick an investor type *</label>
                        <select id="modal_investor_type" name="investor_type" class="form-select" required>
                            <option value="">Select investor type</option>
                            <option value="individual">Myself/an individual</option>
                            <option value="joint">Joint (more than one individual)</option>
                            <option value="corporation">Corporation</option>
                            <option value="trust">Trust</option>
                            <option value="ira">IRA</option>
                        </select>
                    </div>

                    <!-- Individual Investor Fields -->
                    <div id="modal-individual-fields" class="modal-investor-type-fields" style="display: none;">
                        <div class="mb-3">
                            <label for="modal_individual_name" class="form-label">Full Name *</label>
                            <input type="text" id="modal_individual_name" name="individual_name" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="modal_date_of_birth" class="form-label">Date of Birth *</label>
                            <input type="date" id="modal_date_of_birth" name="date_of_birth" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="modal_ssn" class="form-label">Social Security Number *</label>
                            <input type="text" id="modal_ssn" name="ssn" class="form-control" placeholder="XXX-XX-XXXX">
                        </div>
                    </div>

                    <!-- Joint Account Fields -->
                    <div id="modal-joint-fields" class="modal-investor-type-fields" style="display: none;">
                        <div class="mb-3">
                            <label for="modal_primary_name" class="form-label">Primary Account Holder Name *</label>
                            <input type="text" id="modal_primary_name" name="primary_name" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="modal_primary_dob" class="form-label">Primary Holder Date of Birth *</label>
                            <input type="date" id="modal_primary_dob" name="primary_dob" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="modal_primary_ssn" class="form-label">Primary Holder SSN *</label>
                            <input type="text" id="modal_primary_ssn" name="primary_ssn" class="form-control" placeholder="XXX-XX-XXXX">
                        </div>
                        <div class="mb-3">
                            <label for="modal_secondary_name" class="form-label">Secondary Account Holder Name *</label>
                            <input type="text" id="modal_secondary_name" name="secondary_name" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="modal_secondary_dob" class="form-label">Secondary Holder Date of Birth *</label>
                            <input type="date" id="modal_secondary_dob" name="secondary_dob" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="modal_secondary_ssn" class="form-label">Secondary Holder SSN *</label>
                            <input type="text" id="modal_secondary_ssn" name="secondary_ssn" class="form-control" placeholder="XXX-XX-XXXX">
                        </div>
                        <div class="mb-3">
                            <label for="modal_joint_type" class="form-label">Joint Account Type *</label>
                            <select id="modal_joint_type" name="joint_type" class="form-select">
                                <option value="">Select joint type</option>
                                <option value="jtwros">Joint Tenants with Rights of Survivorship</option>
                                <option value="tenants_common">Tenants in Common</option>
                                <option value="community_property">Community Property</option>
                            </select>
                        </div>
                    </div>

                    <!-- Corporation Fields -->
                    <div id="modal-corporation-fields" class="modal-investor-type-fields" style="display: none;">
                        <div class="mb-3">
                            <label for="modal_corporation_name" class="form-label">Corporation Name *</label>
                            <input type="text" id="modal_corporation_name" name="corporation_name" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="modal_ein" class="form-label">Federal Tax ID (EIN) *</label>
                            <input type="text" id="modal_ein" name="ein" class="form-control" placeholder="XX-XXXXXXX">
                        </div>
                        <div class="mb-3">
                            <label for="modal_incorporation_state" class="form-label">State of Incorporation *</label>
                            <input type="text" id="modal_incorporation_state" name="incorporation_state" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Accredited Investor Status *</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="accredited_investor" id="modal_accredited_yes" value="yes">
                                <label class="form-check-label" for="modal_accredited_yes">Yes, I am an accredited investor</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="accredited_investor" id="modal_accredited_no" value="no">
                                <label class="form-check-label" for="modal_accredited_no">No</label>
                            </div>
                        </div>
                    </div>

                    <!-- Trust Fields -->
                    <div id="modal-trust-fields" class="modal-investor-type-fields" style="display: none;">
                        <div class="mb-3">
                            <label for="modal_trust_name" class="form-label">Trust Name *</label>
                            <input type="text" id="modal_trust_name" name="trust_name" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="modal_trust_ein" class="form-label">Trust Tax ID (EIN) *</label>
                            <input type="text" id="modal_trust_ein" name="trust_ein" class="form-control" placeholder="XX-XXXXXXX">
                        </div>
                        <div class="mb-3">
                            <label for="modal_trust_type" class="form-label">Trust Type *</label>
                            <select id="modal_trust_type" name="trust_type" class="form-select">
                                <option value="">Select trust type</option>
                                <option value="revocable">Revocable Trust</option>
                                <option value="irrevocable">Irrevocable Trust</option>
                            </select>
                        </div>
                    </div>

                    <!-- IRA Fields -->
                    <div id="modal-ira-fields" class="modal-investor-type-fields" style="display: none;">
                        <div class="mb-3">
                            <label for="modal_ira_holder_name" class="form-label">Account Holder Name *</label>
                            <input type="text" id="modal_ira_holder_name" name="ira_holder_name" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="modal_ira_type" class="form-label">IRA Type *</label>
                            <select id="modal_ira_type" name="ira_type" class="form-select">
                                <option value="">Select IRA type</option>
                                <option value="traditional">Traditional IRA</option>
                                <option value="roth">Roth IRA</option>
                                <option value="sep">SEP IRA</option>
                                <option value="simple">SIMPLE IRA</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="modal_custodian" class="form-label">IRA Custodian *</label>
                            <input type="text" id="modal_custodian" name="custodian" class="form-control">
                        </div>
                    </div>

                    <div class="alert alert-danger d-none" id="investor-profile-error"></div>
                </form>
            </div>
            <div class="modal-footer">
                {{-- <button type="button" class="btn btn-secondary" id="skip-investor-info">Skip for Now</button> --}}
                <button type="button" class="btn btn-primary" id="save-investor-info">Save & Continue</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const investorModal = document.getElementById('investorInfoModal');
    const investorTypeSelect = document.getElementById('modal_investor_type');
    const saveBtn = document.getElementById('save-investor-info');
    const skipBtn = document.getElementById('skip-investor-info');
    const errorDiv = document.getElementById('investor-profile-error');
    
    // Show/hide fields based on investor type
    if (investorTypeSelect) {
        investorTypeSelect.addEventListener('change', function() {
            // Hide all type-specific fields
            document.querySelectorAll('.modal-investor-type-fields').forEach(el => {
                el.style.display = 'none';
            });
            
            // Show relevant fields
            const selectedType = this.value;
            if (selectedType === 'individual') {
                document.getElementById('modal-individual-fields').style.display = 'block';
            } else if (selectedType === 'joint') {
                document.getElementById('modal-joint-fields').style.display = 'block';
            } else if (selectedType === 'corporation') {
                document.getElementById('modal-corporation-fields').style.display = 'block';
            } else if (selectedType === 'trust') {
                document.getElementById('modal-trust-fields').style.display = 'block';
            } else if (selectedType === 'ira') {
                document.getElementById('modal-ira-fields').style.display = 'block';
            }
        });
    }
    
    // Save investor info
    if (saveBtn) {
        saveBtn.addEventListener('click', async function() {
            errorDiv.classList.add('d-none');
            
            const form = document.getElementById('investor-profile-form');
            const formData = new FormData(form);
            
            // Validation
            const investorType = formData.get('investor_type');
            if (!investorType) {
                errorDiv.textContent = 'Please select an investor type';
                errorDiv.classList.remove('d-none');
                return;
            }
            
            saveBtn.disabled = true;
            saveBtn.textContent = 'Saving...';
            
            try {
                // Get CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || 
                                 document.querySelector('input[name="_token"]')?.value;
                
                const response = await fetch('/users/investor-profile/save', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Close modal and proceed
                    const modalInstance = bootstrap.Modal.getInstance(investorModal);
                    modalInstance.hide();
                    
                    // Trigger continue event
                    window.dispatchEvent(new CustomEvent('investorProfileSaved', { detail: data }));
                } else {
                    errorDiv.textContent = data.message || 'Failed to save investor information';
                    errorDiv.classList.remove('d-none');
                }
            } catch (error) {
                errorDiv.textContent = 'An error occurred. Please try again.';
                errorDiv.classList.remove('d-none');
            } finally {
                saveBtn.disabled = false;
                saveBtn.textContent = 'Save & Continue';
            }
        });
    }
    
    // Skip for now
    if (skipBtn) {
        skipBtn.addEventListener('click', function() {
            const modalInstance = bootstrap.Modal.getInstance(investorModal);
            modalInstance.hide();
            
            // Trigger skip event
            window.dispatchEvent(new CustomEvent('investorProfileSkipped'));
        });
    }
});

// Function to load existing investor profile
window.loadInvestorProfile = function(profileData) {
    console.log('loadInvestorProfile called with:', profileData);
    if (!profileData) return;
    
    const investorTypeSelect = document.getElementById('modal_investor_type');
    if (investorTypeSelect && profileData.investor_type) {
        investorTypeSelect.value = profileData.investor_type;
        investorTypeSelect.dispatchEvent(new Event('change'));
    }
    
    // Load investor_data fields
    if (profileData.investor_data) {
        const data = profileData.investor_data;
        
        // Helper function to set field value
        const setFieldValue = (fieldId, value) => {
            const field = document.getElementById(fieldId);
            if (field && value) {
                field.value = value;
            }
        };
        
        // Individual fields
        setFieldValue('modal_individual_name', data.individual_name);
        setFieldValue('modal_date_of_birth', data.date_of_birth);
        setFieldValue('modal_ssn', data.ssn);
        
        // Joint fields
        setFieldValue('modal_primary_name', data.primary_name);
        setFieldValue('modal_primary_dob', data.primary_dob);
        setFieldValue('modal_primary_ssn', data.primary_ssn);
        setFieldValue('modal_secondary_name', data.secondary_name);
        setFieldValue('modal_secondary_dob', data.secondary_dob);
        setFieldValue('modal_secondary_ssn', data.secondary_ssn);
        setFieldValue('modal_joint_type', data.joint_type);
        
        // Corporation fields
        setFieldValue('modal_corporation_name', data.corporation_name);
        setFieldValue('modal_ein', data.ein);
        setFieldValue('modal_incorporation_state', data.incorporation_state);
        if (data.accredited_investor) {
            const radio = document.querySelector(`input[name="accredited_investor"][value="${data.accredited_investor}"]`);
            if (radio) radio.checked = true;
        }
        
        // Trust fields
        setFieldValue('modal_trust_name', data.trust_name);
        setFieldValue('modal_trust_ein', data.trust_ein);
        setFieldValue('modal_trust_type', data.trust_type);
        
        // IRA fields
        setFieldValue('modal_ira_holder_name', data.ira_holder_name);
        setFieldValue('modal_ira_type', data.ira_type);
        setFieldValue('modal_custodian', data.custodian);
    }
}
</script>
