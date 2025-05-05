document.addEventListener('DOMContentLoaded', function() {
    const problemForm = document.getElementById('problemForm');
    const questionIdInput = document.getElementById('questionId');
    const titleArea = document.getElementById('titleArea');
    const descriptionArea = document.getElementById('descriptionArea');
    const constraintsArea = document.getElementById('constraintsArea');
    const followUpsArea = document.getElementById('followUpsArea');
    const edgeCasesArea = document.getElementById('EdgecasesArea');
    const normalCasesArea = document.getElementById('NormalCasesArea');
    const generateTestCasesBtn = document.getElementById('generateTestCasesBtn');

    const idLoadingSpinner = document.getElementById('idLoadingSpinner');
    const titleLoadingIndicator = document.getElementById('titleLoadingIndicator');
    const descLoadingIndicator = document.getElementById('descLoadingIndicator');
    const generateSpinner = document.getElementById('generateSpinner');
    const generateBtnText = document.getElementById('generateBtnText');
    const edgeCasesLoadingIndicator = document.getElementById('edgeCasesLoadingIndicator');
    const normalCasesLoadingIndicator = document.getElementById('normalCasesLoadingIndicator');
    const submitSpinner = document.getElementById('submitSpinner');
    const loadingOverlay = document.getElementById('loadingOverlay');
    const submitBtn = document.getElementById('submitBtnArea');


    let debounceTimer = null;
    const debounceDelay = 800;

    if (!questionIdInput || !titleArea || !descriptionArea || !problemForm || !generateTestCasesBtn ||
        !idLoadingSpinner || !titleLoadingIndicator || !descLoadingIndicator ||
        !generateSpinner || !generateBtnText || !edgeCasesLoadingIndicator || !normalCasesLoadingIndicator ||
        !submitSpinner || !loadingOverlay || !submitBtn) {
         console.error("Essential form elements, button, or loading indicators not found! Script cannot fully run.");
         if(generateTestCasesBtn) generateTestCasesBtn.disabled = true;
         if(submitBtn) submitBtn.disabled = true;
         return;
    }

    const urlPath = window.location.pathname;
    const segments = urlPath.split('/');
    const lastUrlSegment = segments.filter(segment => segment !== '').pop();

    let platformNameToSend = '';

    if (lastUrlSegment && lastUrlSegment.includes('-form')) {
        const parts = lastUrlSegment.split('-');
        if (parts.length > 0) {
             platformNameToSend = parts[0];
        }
    }
    console.log("Current page URL path:", urlPath);
    console.log("Platform name extracted for AJAX:", platformNameToSend);


    function showTitleDescLoading() {
        titleArea.value = '';
        descriptionArea.value = '';
        idLoadingSpinner.classList.remove('hidden');
        titleLoadingIndicator.classList.remove('hidden');
        descLoadingIndicator.classList.remove('hidden');
    }

    function hideTitleDescLoading() {
        idLoadingSpinner.classList.add('hidden');
        titleLoadingIndicator.classList.add('hidden');
        descLoadingIndicator.classList.add('hidden');
    }

    function showTestCasesLoading() {
        edgeCasesArea.value = '';
        normalCasesArea.value = '';
        generateSpinner.classList.remove('hidden');
        generateBtnText.textContent = 'Generating...';
        generateTestCasesBtn.disabled = true;
        edgeCasesLoadingIndicator.classList.remove('hidden');
        normalCasesLoadingIndicator.classList.remove('hidden');
         if (constraintsArea) constraintsArea.disabled = true;
         if (followUpsArea) followUpsArea.disabled = true;
    }

    function hideTestCasesLoading() {
        generateSpinner.classList.add('hidden');
        generateBtnText.textContent = 'Generate Test Cases';
        generateTestCasesBtn.disabled = false;
        edgeCasesLoadingIndicator.classList.add('hidden');
        normalCasesLoadingIndicator.classList.add('hidden');
         if (constraintsArea) constraintsArea.disabled = false;
         if (followUpsArea) followUpsArea.disabled = false;
    }


    questionIdInput.addEventListener('input', function() {
        const questionId = this.value.trim();

        console.log('Input field value changed. Current ID:', questionId);

        clearTimeout(debounceTimer);

        titleArea.value = '';
        descriptionArea.value = '';
        if (constraintsArea) constraintsArea.value = '';
        if (followUpsArea) followUpsArea.value = '';
        if (edgeCasesArea) edgeCasesArea.value = '';
        if (normalCasesArea) normalCasesArea.value = '';

        hideTitleDescLoading();

        if (!questionId) {
            console.log('Input is empty, areas cleared.');
            return;
        }

        debounceTimer = setTimeout(() => {

            console.log('Debounce timer finished. Fetching description and title for ID:', questionId, 'for platform:', platformNameToSend);

            showTitleDescLoading();
             if (edgeCasesArea) edgeCasesArea.value = '';
             if (normalCasesArea) normalCasesArea.value = '';


            fetch('/generate-descTitle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    id: questionId,
                    platformName: platformNameToSend
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
                const fetchedTitle = (data && data.title && data.title !== 'Title Not found') ? data.title : '';
                const fetchedDescription = (data && data.description && data.description !== 'Description Not found') ? data.description : '';

                titleArea.value = fetchedTitle;
                descriptionArea.value = fetchedDescription;

                 if (!fetchedTitle || !fetchedDescription) {
                    if (constraintsArea) constraintsArea.value = '';
                    if (followUpsArea) followUpsArea.value = '';
                     if (edgeCasesArea) edgeCasesArea.value = '';
                     if (normalCasesArea) normalCasesArea.value = '';
                 }

                console.log("Done processing fetched Title/Description data.");
            })
            .catch(error => {
                console.error('Fetch Error Details (Title/Desc):', error);
                 if (edgeCasesArea) edgeCasesArea.value = '';
                 if (normalCasesArea) normalCasesArea.value = '';

                 const errorTextarea = descriptionArea || titleArea;
                 let displayMessage = `Error fetching details: ${error.message || 'Unknown error'}`;

                 if (error.response && typeof error.response.json === 'function') {
                      error.response.json().then(errData => {
                         console.error('Backend error response body (Title/Desc):', errData);
                         const backendErrorMessage = errData && errData.error ? `Backend Error: ${errData.error}` : `HTTP Error: ${error.response.status} ${error.response.statusText}`;
                         displayMessage = `Error fetching details: ${backendErrorMessage}`;
                          if(errorTextarea) errorTextarea.value = displayMessage;
                          if (constraintsArea) constraintsArea.value = '';
                          if (followUpsArea) followUpsArea.value = '';

                      }).catch(() => {
                           if(errorTextarea) errorTextarea.value = displayMessage;
                           if (constraintsArea) constraintsArea.value = '';
                           if (followUpsArea) followUpsArea.value = '';
                      });
                 } else {
                      if(errorTextarea) errorTextarea.value = displayMessage;
                       if (constraintsArea) constraintsArea.value = '';
                       if (followUpsArea) followUpsArea.value = '';
                 }
            })
            .finally(() => {
                 hideTitleDescLoading();
                 console.log("Title/Description fetch process finished.");
            });

        }, debounceDelay);
    });


    generateTestCasesBtn.addEventListener('click', function() {
        const problemId = questionIdInput.value.trim();
        const problemTitle = titleArea.value.trim();
        const problemDescription = descriptionArea.value.trim();
        const constraints = constraintsArea ? constraintsArea.value.trim() : '';
        const followUps = followUpsArea ? followUpsArea.value.trim() : '';

        if (!problemId || !problemTitle || !problemDescription || problemTitle.startsWith('Error fetching') || problemDescription.startsWith('Error fetching')) {
            console.warn("Cannot generate test cases: Problem details are missing or errored.");
             const feedbackMessage = "Please ensure a valid Problem ID is entered and details are fetched successfully before generating test cases.";
             if (edgeCasesArea) edgeCasesArea.value = feedbackMessage;
             if (normalCasesArea) normalCasesArea.value = '';
             alert(feedbackMessage);
            return;
        }

         if (constraints === '') {
             console.warn("Cannot generate test cases: Constraints are missing.");
             const feedbackMessage = "Please enter Constraints before generating test cases.";
             if (edgeCasesArea) edgeCasesArea.value = feedbackMessage;
             if (normalCasesArea) normalCasesArea.value = '';
             alert(feedbackMessage);
             return;
         }


        console.log('Generating test cases...');
        console.log('Problem ID:', problemId);
        console.log('Problem Title:', problemTitle);
        console.log('Problem Description:', problemDescription);
        console.log('Constraints:', constraints);
        console.log('Follow Ups:', followUps);
        console.log('Platform Name (from URL):', platformNameToSend);

        showTestCasesLoading();


        fetch('/generate-test-cases', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                id: problemId,
                title: problemTitle,
                description: problemDescription,
                constraints: constraints,
                followUps: followUps,
                platformName: platformNameToSend
            })
        })
        .then(response => {
             if (!response.ok) {
                console.error('HTTP error response (Test Cases):', response.status, response.statusText);
                 const error = new Error(`HTTP error! status: ${response.status}`);
                 error.response = response;
                 throw error;
             }
             return response.json();
        })
        .then(data => {
            console.log("Fetched Test Cases data:", data);

            const generatedEdgeCases = data && data.edgeCases ? data.edgeCases : 'Could not generate Edge Cases.';
            const generatedNormalCases = data && data.normalCases ? data.normalCases : 'Could not generate Normal Cases.';

            if (edgeCasesArea) edgeCasesArea.value = generatedEdgeCases;
            if (normalCasesArea) normalCasesArea.value = generatedNormalCases;

            console.log("Done processing fetched Test Cases data.");

        })
        .catch(error => {
            console.error('Fetch Error Details (Test Cases):', error);

             const errorMessagePrefix = 'Error generating test cases: ';
             if (edgeCasesArea) {
                  edgeCasesArea.value = `${errorMessagePrefix}${error.message || 'Unknown error'}`;
                  if (error.response && typeof error.response.json === 'function') {
                       error.response.json().then(errData => {
                          console.error('Backend error response body (Test Cases):', errData);
                           const backendErrorMessage = errData && errData.error ? `Backend Error: ${errData.error}` : `HTTP Error: ${error.response.status} ${error.response.statusText}`;
                           edgeCasesArea.value = `${errorMessagePrefix}${backendErrorMessage}`;
                       }).catch(() => {});
                  }
             }
             if (normalCasesArea) normalCasesArea.value = `See Edge Cases field for the primary error.`;

        })
        .finally(() => {
             hideTestCasesLoading();
             console.log("Test Case generation process finished.");
        });
    });


    problemForm.addEventListener('submit', function(e) {
        const questionIdInput = document.getElementById('questionId');
        const edgeCasesArea = document.getElementById('EdgecasesArea');
        const normalCasesArea = document.getElementById('NormalCasesArea');

        if (questionIdInput && questionIdInput.value.trim() === '') {
            alert('Please enter a Question ID.');
            e.preventDefault();
            return;
        }
        const edgeCasesContent = edgeCasesArea ? edgeCasesArea.value.trim() : '';
        const normalCasesContent = normalCasesArea ? normalCasesArea.value.trim() : '';

        const generatedContentExists = (edgeCasesContent !== '' && !edgeCasesContent.startsWith('Error generating test cases:')) ||
                                       (normalCasesContent !== '' && !normalCasesContent.startsWith('See Edge Cases field for the primary error.'));

        if (!generatedContentExists) {
            alert('Please generate test cases first.');
            e.preventDefault();
            return;
        }

        if(submitSpinner) submitSpinner.classList.remove('hidden');
        if(submitBtn) submitBtn.disabled = true;
        if(loadingOverlay) loadingOverlay.classList.remove('hidden');
    });
});