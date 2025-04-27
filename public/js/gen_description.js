document.addEventListener('DOMContentLoaded', function() {
    const questionIdInput = document.getElementById('questionId');
    const titleArea = document.getElementById('titleArea');
    const descriptionArea = document.getElementById('descriptionArea');
    // Get references for the other text areas as well if you plan to use them later
    const constraintsArea = document.getElementById('constraintsArea');
    const edgeCasesArea = document.getElementById('EdgecasesArea');
    const normalCasesArea = document.getElementById('NormalCasesArea');

    // --- ADDED: Variable to hold the debounce timer ID ---
    let debounceTimer = null;
    const debounceDelay = 3000; // 5000 milliseconds = 5 seconds
    // --- End ADDED ---


    // --- Get last segment of the current page URL and determine platform name ---
    // Ensure required input/output elements exist before proceeding
    if (!questionIdInput || !titleArea || !descriptionArea) {
        console.error("Required elements (questionId, titleArea, descriptionArea) not found in the HTML!");
        // Stop execution if essential elements are missing
        return;
    }

    const urlPath = window.location.pathname;
    const segments = urlPath.split('/');
    // Filter out empty segments and get the last one (e.g., 'leetcode-form', 'codeforces-form', 'root')
    const lastUrlSegment = segments.filter(segment => segment !== '').pop();

    // --- Extract platform name from the last segment ---
    let platformNameToSend = ''; // Default platform name

    if (lastUrlSegment && lastUrlSegment.includes('-form')) {
        const parts = lastUrlSegment.split('-');
        if (parts.length > 0) {
             platformNameToSend = parts[0]; // This will be 'leetcode' or 'codeforces'
        }
    }
    // --- End Extract platform name ---


    console.log("Current page URL path:", urlPath);
    console.log("Last segment of page URL:", lastUrlSegment);
    console.log("Platform name extracted for AJAX:", platformNameToSend);


    // --- Removed: Logic to use the last URL segment to populate the input field on load ---


    // Your existing input event listener for the questionIdInput field
    questionIdInput.addEventListener('input', function() {
        const questionId = this.value.trim(); // Get the current value from the input field

        console.log('Input field value changed. Current ID:', questionId);

        // --- ADDED: Clear the previous timer ---
        clearTimeout(debounceTimer);
        // --- End ADDED ---


        // Clear previous results when input changes or is empty
        if (!questionId) {
            // If input is empty, clear timer, clear fields, and return immediately
            titleArea.value = ''; descriptionArea.value = '';
            if (constraintsArea) constraintsArea.value = '';
            if (edgeCasesArea) edgeCasesArea.value = '';
            if (normalCasesArea) normalCasesArea.value = '';
            console.log('Input is empty, areas cleared.');
            return;
        }

        // --- ADDED: Set a new timer for the fetch operation ---
        // The fetch logic is now inside this setTimeout callback
        debounceTimer = setTimeout(() => {

            console.log('Debounce timer finished. Fetching description and title for ID:', questionId, 'for platform:', platformNameToSend);

            // Clear areas and show loading indicator just before fetching
            titleArea.value = 'Fetching...'; descriptionArea.value = 'Fetching...';

            fetch('/generate-descTitle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest' // Required for Laravel's $request->ajax()
                },
                body: JSON.stringify({
                    id: questionId,
                    platformName: platformNameToSend
                })
            })
            .then(response => {
                 if (!response.ok) {
                    console.error('HTTP error response:', response.status, response.statusText);
                    const error = new Error(`HTTP error! status: ${response.status}`);
                    error.response = response;
                    throw error;
                 }
                 return response.json();
            })
            .then(data => {
                console.log("Fetched data:", data);
                const fetchedTitle = (data && data.title && data.title !== 'Title Not found') ? data.title : '';
                const fetchedDescription = (data && data.description && data.description !== 'Description Not found') ? data.description : '';
                titleArea.value = fetchedTitle;
                descriptionArea.value = fetchedDescription;
                console.log("Done processing fetched data.");
            })
            .catch(error => {
                console.error('Fetch Error Details:', error);
                if (error.response && typeof error.response.json === 'function') {
                     error.response.json().then(errData => {
                        console.error('Backend error response body:', errData);
                        const errorMessage = errData && errData.error ? `Backend Error: ${errData.error}` : `HTTP Error: ${error.response.status} ${error.response.statusText}`;
                        descriptionArea.value = errorMessage;
                        titleArea.value = errorMessage;
                        if (constraintsArea) constraintsArea.value = '';
                        if (edgeCasesArea) edgeCasesArea.value = '';
                        if (normalCasesArea) normalCasesArea.value = '';
                     }).catch(() => {
                         const genericErrorMessage = `Request failed (Non-JSON Error Response): ${error.message || 'Unknown error'}`;
                         descriptionArea.value = genericErrorMessage;
                         titleArea.value = genericErrorMessage;
                         if (constraintsArea) constraintsArea.value = '';
                         if (edgeCasesArea) edgeCasesArea.value = '';
                         if (normalCasesArea) normalCasesArea.value = '';
                     });
                } else {
                    const genericErrorMessage = `Request failed (Network or JS Error): ${error.message || 'Unknown error'}`;
                    descriptionArea.value = genericErrorMessage;
                    titleArea.value = genericErrorMessage;
                    if (constraintsArea) constraintsArea.value = '';
                    if (edgeCasesArea) edgeCasesArea.value = '';
                    if (normalCasesArea) normalCasesArea.value = '';
                }
                 // Ensure areas are cleared/reset on catch if they were showing loading text
                 if(titleArea.value.startsWith('Fetching...')) titleArea.value = '';
                 if(descriptionArea.value.startsWith('Fetching...')) descriptionArea.value = '';
                 if(constraintsArea && constraintsArea.value.startsWith('Fetching...')) constraintsArea.value = '';
                 if(edgeCasesArea && edgeCasesArea.value.startsWith('Fetching...')) edgeCasesArea.value = '';
                 if(normalCasesArea && normalCasesArea.value.startsWith('Fetching...')) normalCasesArea.value = '';
            });

        }, debounceDelay); // Set the timer for the defined delay (5000ms)
        // --- End ADDED ---

    });

    // Removed the optional auto-fetch trigger

});