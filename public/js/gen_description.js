document.addEventListener('DOMContentLoaded', function() {
    // --- Get element references ---
    const problemForm = document.getElementById('problemForm'); // Get the form itself
    const questionIdInput = document.getElementById('questionId');
    const titleArea = document.getElementById('titleArea');
    const descriptionArea = document.getElementById('descriptionArea');
    const constraintsArea = document.getElementById('constraintsArea'); // Get constraints textarea
    const followUpsArea = document.getElementById('followUpsArea'); // Get follow-ups textarea
    const edgeCasesArea = document.getElementById('EdgecasesArea'); // Get edge cases output textarea
    const normalCasesArea = document.getElementById('NormalCasesArea'); // Get normal cases output textarea
    const generateTestCasesBtn = document.getElementById('generateTestCasesBtn'); // Get the button
    // --- End Get element references ---


    // --- Variable to hold the debounce timer ID for the INPUT event ---
    let debounceTimer = null;
    const debounceDelay = 5000; // 5000 milliseconds = 5 seconds
    // --- End Debounce Timer ---


    // --- Get last segment of the current page URL and determine platform name (on page load) ---
    // Ensure required elements for the primary fetch and button exist before proceeding
    if (!questionIdInput || !titleArea || !descriptionArea || !problemForm || !generateTestCasesBtn) {
         console.error("Essential form elements or button not found! Script cannot fully run.");
         // Disable button or stop execution if core elements are missing
         if(generateTestCasesBtn) generateTestCasesBtn.disabled = true;
         // Return here if crucial elements are missing
         return;
    }

    const urlPath = window.location.pathname;
    const segments = urlPath.split('/');
    // Filter out empty segments and get the last one (e.g., 'leetcode-form', 'codeforces-form', 'root')
    const lastUrlSegment = segments.filter(segment => segment !== '').pop();

    // --- Extract platform name from the last segment (on page load) ---
    let platformNameToSend = ''; // Default platform name

    if (lastUrlSegment && lastUrlSegment.includes('-form')) {
        const parts = lastUrlSegment.split('-');
        if (parts.length > 0) {
             platformNameToSend = parts[0]; // This will be 'leetcode' or 'codeforces'
        }
    }
    console.log("Current page URL path:", urlPath);
    console.log("Platform name extracted for AJAX:", platformNameToSend);
    // --- End platform name extraction ---


    // --- Primary Input Event Listener (for fetching title/description) ---
    questionIdInput.addEventListener('input', function() {
        const questionId = this.value.trim(); // Get the current value from the input field

        console.log('Input field value changed. Current ID:', questionId);

        clearTimeout(debounceTimer); // Clear the previous timer

        // Clear previous results when input changes or is empty
        if (!questionId) {
            // If input is empty, clear timer, clear fields, and return immediately
            titleArea.value = ''; descriptionArea.value = '';
            // Clear test case inputs/outputs too when ID is cleared
            if (constraintsArea) constraintsArea.value = '';
            if (followUpsArea) followUpsArea.value = '';
            if (edgeCasesArea) edgeCasesArea.value = '';
            if (normalCasesArea) normalCasesArea.value = '';

            console.log('Input is empty, areas cleared.');
            return; // Stop execution if input is empty
        }

        // --- Set a new timer for the title/description fetch operation ---
        debounceTimer = setTimeout(() => {

            console.log('Debounce timer finished. Fetching description and title for ID:', questionId, 'for platform:', platformNameToSend);

            // Show loading indicator for title/description
            titleArea.value = 'Fetching...';
            descriptionArea.value = 'Fetching...';
            // Clear test case outputs while fetching new problem details
            if (edgeCasesArea) edgeCasesArea.value = '';
            if (normalCasesArea) normalCasesArea.value = '';


            fetch('/generate-descTitle', { // Endpoint for Title/Description
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    id: questionId,
                    platformName: platformNameToSend // Send platform name
                })
            })
            .then(response => {
                 if (!response.ok) {
                    console.error('HTTP error response (Title/Desc):', response.status, response.statusText);
                    const error = new Error(`HTTP error! status: ${response.status}`);
                    error.response = response;
                    throw error;
                 }
                 return response.json();
            })
            .then(data => {
                console.log("Fetched Title/Description data:", data);
                // Check for 'Not found' strings and populate areas or clear them
                const fetchedTitle = (data && data.title && data.title !== 'Title Not found') ? data.title : '';
                const fetchedDescription = (data && data.description && data.description !== 'Description Not found') ? data.description : '';

                titleArea.value = fetchedTitle;
                descriptionArea.value = fetchedDescription;

                // Clear test case inputs if title/desc not found (implies problem ID might be wrong)
                 if (!fetchedTitle || !fetchedDescription) {
                    if (constraintsArea) constraintsArea.value = '';
                    if (followUpsArea) followUpsArea.value = '';
                     // Also clear test case outputs
                     if (edgeCasesArea) edgeCasesArea.value = '';
                     if (normalCasesArea) normalCasesArea.value = '';
                 }


                console.log("Done processing fetched Title/Description data.");
            })
            .catch(error => {
                console.error('Fetch Error Details (Title/Desc):', error);
                // Keep test case outputs clear on error
                 if (edgeCasesArea) edgeCasesArea.value = '';
                 if (normalCasesArea) normalCasesArea.value = '';

                if (error.response && typeof error.response.json === 'function') {
                     error.response.json().then(errData => {
                        console.error('Backend error response body (Title/Desc):', errData);
                        const errorMessage = errData && errData.error ? `Backend Error: ${errData.error}` : `HTTP Error: ${error.response.status} ${error.response.statusText}`;
                        descriptionArea.value = errorMessage;
                        titleArea.value = errorMessage;
                         // Clear related inputs on error
                        if (constraintsArea) constraintsArea.value = '';
                        if (followUpsArea) followUpsArea.value = '';

                     }).catch(() => {
                         const genericErrorMessage = `Request failed (Non-JSON Error Response - Title/Desc): ${error.message || 'Unknown error'}`;
                         descriptionArea.value = genericErrorMessage;
                         titleArea.value = genericErrorMessage;
                          // Clear related inputs on error
                         if (constraintsArea) constraintsArea.value = '';
                         if (followUpsArea) followUpsArea.value = '';
                     });
                } else {
                    const genericErrorMessage = `Request failed (Network or JS Error - Title/Desc): ${error.message || 'Unknown error'}`;
                    descriptionArea.value = genericErrorMessage;
                    titleArea.value = genericErrorMessage;
                     // Clear related inputs on error
                    if (constraintsArea) constraintsArea.value = '';
                    if (followUpsArea) followUpsArea.value = '';
                }
                 // Ensure areas are cleared/reset on catch if showing loading text
                 if(titleArea.value.startsWith('Fetching...')) titleArea.value = '';
                 if(descriptionArea.value.startsWith('Fetching...')) descriptionArea.value = '';
            });

        }, debounceDelay); // Set the timer for the defined delay
    });
    // --- End Primary Input Listener ---


    // --- Event Listener for Test Case Generation Button ---
    generateTestCasesBtn.addEventListener('click', function() {
        // Get ALL necessary data from the form fields AT THE TIME OF BUTTON CLICK
        const problemId = questionIdInput.value.trim();
        const problemTitle = titleArea.value.trim();
        const problemDescription = descriptionArea.value.trim();
        const constraints = constraintsArea ? constraintsArea.value.trim() : ''; // Use empty string if area not found
        const followUps = followUpsArea ? followUpsArea.value.trim() : ''; // Use empty string if area not found
        // platformNameToSend is captured once on page load, still accessible here

        // Ensure essential data is present before trying to generate cases
        if (!problemId || !problemTitle || !problemDescription) {
            console.warn("Cannot generate test cases: Problem ID, Title, or Description is missing.");
            // Provide user feedback
            const feedbackMessage = "Please ensure Problem ID is entered and Title/Description are fetched before generating test cases.";
             if (edgeCasesArea) edgeCasesArea.value = feedbackMessage;
             if (normalCasesArea) normalCasesArea.value = ''; // Clear normal cases area
             if (titleArea && descriptionArea) { // Add alert only if Title/Desc fields exist
                 alert(feedbackMessage);
             }
            return; // Stop if essential info is missing
        }

        console.log('Generating test cases...');
        console.log('Problem ID:', problemId);
        console.log('Problem Title:', problemTitle);
        console.log('Problem Description:', problemDescription);
        console.log('Constraints:', constraints);
        console.log('Follow Ups:', followUps);
        console.log('Platform Name (from URL):', platformNameToSend); // Log the platform name


        // Show loading indicators for test case areas
        if (edgeCasesArea) edgeCasesArea.value = 'Generating...';
        if (normalCasesArea) normalCasesArea.value = 'Generating...';

        // Make the AJAX request to the new backend endpoint
        fetch('/generate-test-cases', { // Endpoint for Test Case Generation
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                id: problemId, // Problem ID (from input)
                title: problemTitle, // Problem Title (from fetched value)
                description: problemDescription, // Problem Description (from fetched value)
                constraints: constraints, // Constraints input by user
                followUps: followUps, // Follow ups input by user
                platformName: platformNameToSend // Platform name (from URL context)
            })
        })
        .then(response => {
             if (!response.ok) {
                console.error('HTTP error response (Test Cases):', response.status, response.statusText);
                 const error = new Error(`HTTP error! status: ${response.status}`);
                 error.response = response;
                 throw error;
             }
             return response.json(); // Expecting JSON: { edgeCases: "...", normalCases: "..." }
        })
        .then(data => {
            console.log("Fetched Test Cases data:", data);

            // Populate the test case text areas with the received data
            // Check if data exists and properties exist
            const generatedEdgeCases = data && data.edgeCases ? data.edgeCases : 'Could not generate Edge Cases.';
            const generatedNormalCases = data && data.normalCases ? data.normalCases : 'Could not generate Normal Cases.';

            if (edgeCasesArea) edgeCasesArea.value = generatedEdgeCases;
            if (normalCasesArea) normalCasesArea.value = generatedNormalCases;

            console.log("Done processing fetched Test Cases data.");

        })
        .catch(error => {
            // --- Catch Block for Test Case Generation ---
            console.error('Fetch Error Details (Test Cases):', error);

            // Set error messages in test case areas
             const errorMessagePrefix = 'Error generating test cases: ';
             if (edgeCasesArea) edgeCasesArea.value = `${errorMessagePrefix}${error.message || 'Unknown error'}`;
             if (normalCasesArea) normalCasesArea.value = `See Edge Cases field for the primary error.`;


            // Attempt to read the error response body
            if (error.response && typeof error.response.json === 'function') {
                 error.response.json().then(errData => {
                    console.error('Backend error response body (Test Cases):', errData);
                     const backendErrorMessage = errData && errData.error ? `Backend Error: ${errData.error}` : `HTTP Error: ${error.response.status} ${error.response.statusText}`;
                     if (edgeCasesArea) edgeCasesArea.value = `${errorMessagePrefix}${backendErrorMessage}`;
                     if (normalCasesArea) normalCasesArea.value = `See Edge Cases field for the primary error.`;

                 }).catch(() => {
                     // If JSON parsing of error response fails, generic message remains
                 });
            }
             // Ensure areas are cleared/reset on catch if showing loading text
            if (edgeCasesArea && edgeCasesArea.value.startsWith('Generating...')) edgeCasesArea.value = '';
            if (normalCasesArea && normalCasesArea.value.startsWith('Generating...')) normalCasesArea.value = '';
            // --- End Catch Block ---
        });
    });
    // --- End Button Listener ---


    // Removed the optional auto-fetch trigger

});