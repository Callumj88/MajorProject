            <!-- ========== APPOINTMENT FORM SECTION ========== -->
            <div class="appointment-container">

<!-- LEFT SIDE: Heading & Text -->
<div class="appointment-info">
  <h2 class="appointment-title">REQUEST AN APPOINTMENT</h2>
  <p class="appointment-desc">
    Lorem Ipsum is simply dummy text of the printing and 
    typesetting industry. Lorem Ipsum has been the 
    industry's standard dummy text.
  </p>
</div>

<!-- RIGHT SIDE: Appointment Form -->
<div class="appointment-form-container">
  <form action="#" method="POST" class="appointment-form">
    <fieldset>
      <!-- Row 1: Full Name + Email -->
      <div class="form-row">
        <div class="fs-field">
          <label class="fs-label" for="full-name">Full Name*</label>
          <input class="fs-input" id="full-name" name="full-name" required />
        </div>
        <div class="fs-field">
          <label class="fs-label" for="email">Email*</label>
          <input class="fs-input" id="email" name="email" required />
        </div>
      </div>

      <!-- Row 2: Phone Number + Vehicle Type -->
      <div class="form-row">
        <div class="fs-field">
          <label class="fs-label" for="phone-number">Phone Number*</label>
          <input class="fs-input" id="phone-number" name="phone-number" required />
        </div>
        <div class="fs-field">
          <label class="fs-label" for="vehicle-type">Vehicle Type*</label>
          <input class="fs-input" id="vehicle-type" name="vehicle-type" required />
        </div>
      </div>

      <!-- Row 3: Select Time + Select Date -->
      <div class="form-row">
        <div class="fs-field">
          <label class="fs-label" for="select-time">Select Time*</label>
          <input class="fs-input" type="time" id="select-time" name="select-time" required />
        </div>
        <div class="fs-field">
          <label class="fs-label" for="select-date">Select Date*</label>
          <input class="fs-input" type="date" id="select-date" name="select-date" required />
        </div>
      </div>

      <!-- Row 4: Your Message (Full Width) -->
      <div class="form-row single-column">
        <div class="fs-field">
          <label class="fs-label" for="message">Your Message*</label>
          <textarea class="fs-textarea" id="message" name="message" required></textarea>
        </div>
      </div>
    </fieldset>

    <!-- Big Submit Button -->
    <div class="fs-button-group">
      <button class="fs-button appointment-btn" type="submit">
        APPOINTMENT NOW
      </button>
    </div>
  </form>
</div>
</div>
<!-- ========== END APPOINTMENT SECTION ========== -->