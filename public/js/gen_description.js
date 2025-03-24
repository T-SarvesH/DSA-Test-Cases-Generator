
document.addEventListener('DOMContentLoaded', function() {
    const questionIdInput = document.getElementById('questionId');
    const titleArea = document.getElementById('titleArea'); 
    const descriptionArea = document.getElementById('descriptionArea');

    questionIdInput.addEventListener('input', function() {
        const questionId = this.value;

        console.log(questionId);

        if (questionId) {

            console.log('Fetching description and title');
            fetch('/generate-descTitle', { 
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ id: questionId })
            })
            .then(response => response.json())
            .then(data => {

                console.log("fetched the data");
                console.log(data);
                titleArea.value = data.title;
                descriptionArea.value = data.description;

                console.log("Done fetching the data");
            
            })
            .catch(error => {
                console.error('Error:', error);
                descriptionArea.value = 'Network error fetching description.';
                titleArea.value = 'Network error fetching title.';
            });
        } 
    });
});