/**
 * WordPress dependencies
 */
import { store, getContext, getElement } from "@wordpress/interactivity";

const { state } = store("warroom-block", {
  state: {
    get callrailKickAssComputed() {
      const context = getContext();
      return (
        parseInt(context.callrailKickAssCompare) -
        parseInt(context.callrailKickAss)
      );
    },
    get callrailKickAssComputedColor() {
      const context = getContext();
      return (
        Math.sign(
          parseInt(context.callrailKickAssCompare) -
            parseInt(context.callrailKickAss)
        ) === -1
      );
    },
    get callrailNeedsFuelComputed() {
      const context = getContext();
      return (
        parseInt(context.callrailNeedsFuelCompare) -
        parseInt(context.callrailNeedsFuel)
      );
    },
    get callrailNeedsFuelComputedColor() {
      const context = getContext();
      return (
        Math.sign(
          parseInt(context.callrailNeedsFuelCompare) -
            parseInt(context.callrailNeedsFuel)
        ) === -1
      );
    },
    get hubspotComputed() {
      const context = getContext();
      return parseInt(context.hubspotCompare) - parseInt(context.hubspot);
    },
    get hubspotCompareComputedColor() {
      const context = getContext();
      console.log(
        parseInt(context.hubspotCompare),
        parseInt(context.hubspot),
        Math.sign(
          parseInt(context.hubspotCompare) - parseInt(context.hubspot)
        ) === -1
      );
      return (
        Math.sign(
          parseInt(context.hubspotCompare) - parseInt(context.hubspot)
        ) === -1
      );
    }
  },
  actions: {
    *submitForm(e) {
      e.preventDefault();
      const { ref } = getElement();
      const formData = new FormData(ref);

      const context = getContext();

      formData.append("action", "date_filter");
      formData.append("nonce", state.nonce);

      const notif = document.getElementById("notification");
      if (notif) {
        notif.style.display = "block";
        notif.querySelector("#processing").style.display = "block";
      }

      const data = yield fetch(state.ajaxUrl, {
        method: "POST",
        body: formData
      }).then((response) => response.json());

      if (data.status == "success") {
        context.hubspot = data.data.hubspot;
        context.callrailKickAss = data.data.callRailKickAss;
        context.callrailNeedsFuel = data.data.callRailNeedsFuel;
        // Compare
        context.hubspotCompare = data.data.hubspotCompare;
        context.callrailKickAssCompare = data.data.callrailKickAssCompare;
        context.callrailNeedsFuelCompare = data.data.callrailNeedsFuelCompare;

        if (notif) {
          notif.querySelector("#processing").style.display = "none";
          notif.querySelector("#success").style.display = "block";
        }
        setTimeout(function () {
          const notif = document.getElementById("notification");
          if (notif) {
            notif.style.display = "none";
            notif.querySelector("#processing").style.display = "none";
            notif.querySelector("#success").style.display = "none";
          }
        }, 2000);
      }
    }
  },
  callbacks: {}
});
