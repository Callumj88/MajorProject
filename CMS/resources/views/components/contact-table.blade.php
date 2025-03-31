<!-- ========== NEW DETAILS SECTION ========== -->
</main>
      <section class="details-section">
        <div class="details-section-inner">
          <!-- LEFT SIDE: Heading & Text -->
          <div class="details-text">
            <h2 class="details-heading">How can we help?</h2>
            <p class="details-desc">
              Lorem ipsum simply dummy text of the printing 
              and typesetting industry. Lorem Ipsum has been 
              the industry's standard dummy text.
            </p>
          </div>

          <!-- RIGHT SIDE: Table of business details -->
          <div class="details-table-container">
            <table class="business-table">
              <tbody>
                <tr>
                  <td>Mail:</td>
                  <td>{{ businessContact('email') }}<br>{{ businessContact('email2') }}</td>
                </tr>
                <tr>
                  <td>Location:</td>
                  <td>{{ businessContact(key: 'address') }}</td>
                </tr>
                <tr>
                  <td>Phone:</td>
                  <td>{{ businessContact(key: 'phone') }}</td>
                </tr>
                <tr>
                  <td>Open Hours:</td>
                  <td>{{ businessContact('openingTimes') }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </section>
      <main>
      <!-- ========== END NEW DETAILS SECTION ========== -->