<script>
    document.addEventListener('DOMContentLoaded', function() {
        const faqItems = document.querySelectorAll('.faq-item');
        faqItems.forEach(item => {
            const question = item.querySelector('.question');
            question.addEventListener('click', function() {
                // Accordion: close others
                faqItems.forEach(i => {
                    if (i !== item) i.classList.remove('active');
                });
                item.classList.toggle('active');
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
                    <p>You must login in the SGRMS portal. Then you can click the "Request Appointment" button under any
                        counselor's profile. Choose your
                        preferred
                        date and time, and wait for confirmation.
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
        <div class="cta-box">
            <div class="flex-cta">
                <div class="cta-content">
                    <p>Still have questions or need someone to talk to?</p>
                    <div class="cta-btn">
                        <button class="btn-primary">Visit the Guidance Center
                            <i class="fi fi-rr-marker"></i>
                        </button>
                        <button class="btn-secondary">Request Counseling
                            <i class="fi fi-rr-arrow-small-right"></i>
                        </button>
                    </div>
                </div>
                <div>
                    <div class="icon-img">
                        <img src="{{ asset('images/img/assistance.png') }}" alt="assistance">
                    </div>
                </div>

            </div>
        </div>
    </div>

</section>
