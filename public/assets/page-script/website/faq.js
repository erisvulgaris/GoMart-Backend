function toggleFaq(clickedBtn) {
    document.querySelectorAll('.space-y-2 > div > div').forEach(div => {
        if (div.previousElementSibling !== clickedBtn) {
            div.classList.add('hidden');
        }
    });
    
    let answerDiv = clickedBtn.nextElementSibling;
    answerDiv.classList.toggle('hidden');
}