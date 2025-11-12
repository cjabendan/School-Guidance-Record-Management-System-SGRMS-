<script>
document.addEventListener('DOMContentLoaded', function() {
    const faqItems = document.querySelectorAll('.faq-item');
    faqItems.forEach(item => {
        const question = item.querySelector('.question');
        const icon = item.querySelector('.plus-btn i');

        question.addEventListener('click', function() {
            faqItems.forEach(i => {
                if (i !== item) {
                    i.classList.remove('active');
                    i.querySelector('.plus-btn i').className = 'fi fi-br-plus';
                }
            });

            item.classList.toggle('active');
            icon.className = item.classList.contains('active') 
                ? 'fi fi-br-angle-up'
                : 'fi fi-br-plus';
        });
    });
});

</script>

<section class="faq-section" id="faq">
    <div class="content">
        <h2>Frequently Asked Questions</h2>
        <div class="faq-container">
            <div class="faq-item">
                <div class="question">
                    <div class="flex-row">
                        <i class="fi fi-sr-interrogation"></i>
                        <h3>How do I book an appointment with a guidance counselor?</h3>
                    </div>
                    <div class="plus-btn">
                        <i class="fi fi-br-plus"></i>
                    </div>
                </div>
                <div class="answer">
                    <p> You must log in to the SGRMS portal. Navigate to "Appointments," request a counseling session, fill in the 
                        necessary information, and then you’re done — just wait for confirmation.
                    </p>
                </div>
            </div>
            <div class="faq-item">
                <div class="question">
                    <div class="flex-row">
                        <i class="fi fi-sr-interrogation"></i>
                        <h3>Can I talk to a counselor even if it's not about academics?</h3>
                    </div>
                    <div class="plus-btn">
                        <i class="fi fi-br-plus"></i>
                    </div>
                </div>
                <div class="answer">
                    <p>Yes! Our counselors are here to help with personal concerns, mental health, career planning, and
                        more—whatever you're going through.</p>
                </div>
            </div>
            <div class="faq-item">
                <div class="question">
                    <div class="flex-row">
                        <i class="fi fi-sr-interrogation"></i>
                        <h3>Is everything I share during counseling kept confidential?</h3>
                    </div>
                    <div class="plus-btn">
                        <i class="fi fi-br-plus"></i>
                    </div>
                </div>
                <div class="answer">
                    <p>
                        Absolutely. Your privacy matters to us. Everything discussed is confidential unless there's a
                        risk of harm or a safety concern.
                    </p>
                </div>
            </div>
            <div class="faq-item">
                <div class="question">
                    <div class="flex-row">
                        <i class="fi fi-sr-interrogation"></i>
                        <h3>Can parents also schedule an appointment with the guidance team?</h3>
                    </div>
                    <div class="plus-btn">
                        <i class="fi fi-br-plus"></i>
                    </div>
                </div>
                <div class="answer">
                    <p>Yes, parents are welcome to request appointments, especially if they have questions or concerns
                        about their child's well-being.</p>
                </div>
            </div>
            <div class="faq-item">
                <div class="question">
                    <div class="flex-row">
                        <i class="fi fi-sr-interrogation"></i>
                        <h3>What if I’m not sure who to talk to?</h3>
                    </div>
                    <div class="plus-btn">
                        <i class="fi fi-br-plus"></i>
                    </div>
                </div>
                <div class="answer">
                    <p>No worries—just send a general appointment request or visit the guidance office. We'll make sure
                        you're connected to the right person.</p>
                </div>
            </div>
        </div>
       
    </div>

</section>
