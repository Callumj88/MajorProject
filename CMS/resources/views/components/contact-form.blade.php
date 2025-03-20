      <!-- Contact Form Section -->
      <div class="contact-container">
        <!-- LEFT SIDE: Heading & Description -->
        <div class="contact-info-section">
          <h2 class="contact-title">CONTACT US</h2>
          <p class="contact-desc">
            Lorem Ipsum is simply dummy text of the printing and
            typesetting industry. Lorem Ipsum has been the
            industry's standard dummy text.
          </p>
        </div>

        <!-- RIGHT SIDE: Form -->
        <div class="contact-form-container">
          <form action="#" method="POST" class="contact-form">
            <fieldset>
              <!-- Name + Email -->
              <div class="form-row">
                <div class="fs-field">
                  <label class="fs-label" for="name">Name*</label>
                  <input class="fs-input" id="name" name="name" required />
                </div>
                <div class="fs-field">
                  <label class="fs-label" for="email">Email*</label>
                  <input class="fs-input" id="email" name="email" required />
                </div>
              </div>

              <!-- Subject + Phone -->
              <div class="form-row">
                <div class="fs-field">
                  <label class="fs-label" for="subject">Subject*</label>
                  <input class="fs-input" id="subject" name="subject" required />
                </div>
                <div class="fs-field">
                  <label class="fs-label" for="phone">Phone</label>
                  <input class="fs-input" id="phone" name="phone" />
                </div>
              </div>

              <!-- Message -->
              <div class="form-row single-column">
                <div class="fs-field">
                  <label class="fs-label" for="message">Your Message*</label>
                  <textarea
                    class="fs-textarea"
                    id="message"
                    name="message"
                    required
                  ></textarea>
                </div>
              </div>
            </fieldset>

            <!-- Big Submit Button -->
            <div class="fs-button-group">
              <button class="fs-button contact-btn" type="submit">
                SEND MESSAGE
              </button>
            </div>
          </form>
        </div>
      </div>
      <!-- END of Contact Form -->