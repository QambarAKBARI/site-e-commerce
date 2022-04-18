
const stripe = Stripe(stripePublicKey);
const elements = stripe.elements();
const card = elements.create("card");
const cardHolderName = document.getElementById("cardholder-name");


// Stripe injects an iframe into the DOM
card.mount("#card-element");
card.on("change", function (event) {
  // Disable the Pay button if there are no card details in the Element
  document.querySelector("button").disabled = event.empty;
  document.querySelector("#card-error").textContent = event.error ? event.error.message : "";
});
const form = document.getElementById("payment-form");

form.addEventListener("submit", function (event) {
  event.preventDefault();
  // Complete payment when the submit button is clicked
  stripe
    .confirmCardPayment(clientSecret, {
      payment_method: {
        card: card,
        billing_details: {
        name: cardHolderName.value
        },
      },
      
    })
    .then(function (result) {
      if (result.error) {
        // Show error to your customer
        document.getElementById("errors").innerText = result.error.message;
      } else {
        // The payment succeeded!
        window.location.href = redirectAfterSuccessUrl;
      }
    });
});
