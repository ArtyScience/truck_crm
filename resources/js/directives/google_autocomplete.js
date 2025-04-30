import {
    defineComponent as k,
    ref as r,
    onMounted as A,
    nextTick as P,
    onBeforeUnmount as B,
    openBlock as M,
    createElementBlock as S,
    normalizeClass as x
} from "vue";

const b = ["placeholder"], F = /* @__PURE__ */ k({
    __name: "GoogleAutocomplete",
    props: {
        modelValue: {
            type: String,
            default: ""
        },
        apiKey: {
            type: String,
            required: !0
        },
        placeholder: {
            type: String,
            default: ""
        },
        isFullPayload: {
            type: Boolean,
            default: !1
        },
        class: {
            type: String,
            default: ""
        },
    },
    emits: ["update:modelValue", "set"],
    setup(n, {emit: s}) {
        const d = n, i = r(), e = r(), p = r(!1), _ = () => new Promise((t, o) => {
            if (window.google && window.google.maps && window.google.maps.places)
                t();
            else if (!p.value) {
                p.value = !0;
            }
        }), v = () => {
            if (i.value) {
                const t = google.maps.places, o = new t.Autocomplete(i.value, {
                    fields: ["formatted_address", "address_components", "geometry", "name"],
                    componentRestrictions: { country: ['us', 'ca'] },
                    strictBounds: !1
                });

                o.addListener("place_changed", async () => {
                    var y, f, w;
                    e.value = await o.getPlace();

                    const l = await e.value.geometry.location.lat(), c = await e.value.geometry.location.lng();
                    let u = "", m = "", g = "";

                    for (const a of (y = e.value) == null ? void 0 : y.address_components)
                        a.types.includes("locality") ? u = await a.long_name : a.types.includes
                        ("administrative_area_level_1") ? m = await a.long_name : a.types.includes("country")
                            && (g = await a.long_name)

                    const h = {
                        name: (f = e.value) == null ? void 0 : f.name,
                        city: u,
                        state: m,
                        country: g,
                        latitude: l,
                        longitude: c
                    };

                    s("update:modelValue", (w = e.value) == null ? void 0 : w.name), d.isFullPayload ? s("set", e.value) : s("set", h);

                });
            }
        };
        return A(async () => {
            try {
                await _(), await P(), v();
            } catch (t) {
                console.error("Failed to load Google Maps API", t);
            }
        }), B(() => {
            delete window.initMap;
        }), (t, o) => (M(), S("input", {
            ref_key: "origin",
            ref: i,
            type: "text",
            class: x(n.class),
            placeholder: n.placeholder
        }, null, 10, b));
    }
});
export {
    F as GoogleAutocomplete
};
