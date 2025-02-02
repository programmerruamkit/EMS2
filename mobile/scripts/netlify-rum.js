(() => {
    "use strict";
    var e, n, t, r, i, o, a = -1,
        c = function (e) {
            addEventListener("pageshow", (function (n) {
                n.persisted && (a = n.timeStamp, e(n))
            }), !0)
        },
        u = function () {
            return window.performance && performance.getEntriesByType && performance.getEntriesByType("navigation")[0]
        },
        s = function () {
            var e = u();
            return e && e.activationStart || 0
        },
        f = function (e, n) {
            var t = u(),
                r = "navigate";
            return a >= 0 ? r = "back-forward-cache" : t && (r = document.prerendering || s() > 0 ? "prerender" : document.wasDiscarded ? "restore" : t.type.replace(/_/g, "-")), {
                name: e,
                value: void 0 === n ? -1 : n,
                rating: "good",
                delta: 0,
                entries: [],
                id: "v3-".concat(Date.now(), "-").concat(Math.floor(8999999999999 * Math.random()) + 1e12),
                navigationType: r
            }
        },
        d = function (e, n, t) {
            try {
                if (PerformanceObserver.supportedEntryTypes.includes(e)) {
                    var r = new PerformanceObserver((function (e) {
                        Promise.resolve().then((function () {
                            n(e.getEntries())
                        }))
                    }));
                    return r.observe(Object.assign({
                        type: e,
                        buffered: !0
                    }, t || {})), r
                }
            } catch (e) {}
        },
        l = function (e, n, t, r) {
            var i, o;
            return function (a) {
                n.value >= 0 && (a || r) && ((o = n.value - (i || 0)) || void 0 === i) && (i = n.value, n.delta = o, n.rating = function (e, n) {
                    return e > n[1] ? "poor" : e > n[0] ? "needs-improvement" : "good"
                }(n.value, t), e(n))
            }
        },
        v = function (e) {
            requestAnimationFrame((function () {
                return requestAnimationFrame((function () {
                    return e()
                }))
            }))
        },
        p = function (e) {
            var n = function (n) {
                "pagehide" !== n.type && "hidden" !== document.visibilityState || e(n)
            };
            addEventListener("visibilitychange", n, !0), addEventListener("pagehide", n, !0)
        },
        h = function (e) {
            var n = !1;
            return function (t) {
                n || (e(t), n = !0)
            }
        },
        m = -1,
        g = function () {
            return "hidden" !== document.visibilityState || document.prerendering ? 1 / 0 : 0
        },
        y = function (e) {
            "hidden" === document.visibilityState && m > -1 && (m = "visibilitychange" === e.type ? e.timeStamp : 0, b())
        },
        w = function () {
            addEventListener("visibilitychange", y, !0), addEventListener("prerenderingchange", y, !0)
        },
        b = function () {
            removeEventListener("visibilitychange", y, !0), removeEventListener("prerenderingchange", y, !0)
        },
        E = function () {
            return m < 0 && (m = g(), w(), c((function () {
                setTimeout((function () {
                    m = g(), w()
                }), 0)
            }))), {
                get firstHiddenTime() {
                    return m
                }
            }
        },
        T = function (e) {
            document.prerendering ? addEventListener("prerenderingchange", (function () {
                return e()
            }), !0) : e()
        },
        S = function (e, n) {
            n = n || {}, T((function () {
                var t, r = [1800, 3e3],
                    i = E(),
                    o = f("FCP"),
                    a = d("paint", (function (e) {
                        e.forEach((function (e) {
                            "first-contentful-paint" === e.name && (a.disconnect(), e.startTime < i.firstHiddenTime && (o.value = Math.max(e.startTime - s(), 0), o.entries.push(e), t(!0)))
                        }))
                    }));
                a && (t = l(e, o, r, n.reportAllChanges), c((function (i) {
                    o = f("FCP"), t = l(e, o, r, n.reportAllChanges), v((function () {
                        o.value = performance.now() - i.timeStamp, t(!0)
                    }))
                })))
            }))
        },
        L = {
            passive: !0,
            capture: !0
        },
        C = new Date,
        A = function (r, i) {
            e || (e = i, n = r, t = new Date, k(removeEventListener), x())
        },
        x = function () {
            if (n >= 0 && n < t - C) {
                var i = {
                    entryType: "first-input",
                    name: e.type,
                    target: e.target,
                    cancelable: e.cancelable,
                    startTime: e.timeStamp,
                    processingStart: e.timeStamp + n
                };
                r.forEach((function (e) {
                    e(i)
                })), r = []
            }
        },
        M = function (e) {
            if (e.cancelable) {
                var n = (e.timeStamp > 1e12 ? new Date : performance.now()) - e.timeStamp;
                "pointerdown" == e.type ? function (e, n) {
                    var t = function () {
                            A(e, n), i()
                        },
                        r = function () {
                            i()
                        },
                        i = function () {
                            removeEventListener("pointerup", t, L), removeEventListener("pointercancel", r, L)
                        };
                    addEventListener("pointerup", t, L), addEventListener("pointercancel", r, L)
                }(n, e) : A(n, e)
            }
        },
        k = function (e) {
            ["mousedown", "keydown", "touchstart", "pointerdown"].forEach((function (n) {
                return e(n, M, L)
            }))
        },
        P = 0,
        O = 1 / 0,
        I = 0,
        D = function (e) {
            e.forEach((function (e) {
                e.interactionId && (O = Math.min(O, e.interactionId), I = Math.max(I, e.interactionId), P = I ? (I - O) / 7 + 1 : 0)
            }))
        },
        F = function () {
            return i ? P : performance.interactionCount || 0
        },
        W = function () {
            "interactionCount" in performance || i || (i = d("event", D, {
                type: "event",
                buffered: !0,
                durationThreshold: 0
            }))
        },
        q = 0,
        B = function () {
            return F() - q
        },
        H = [],
        _ = {},
        R = function (e) {
            var n = H[H.length - 1],
                t = _[e.interactionId];
            if (t || H.length < 10 || e.duration > n.latency) {
                if (t) t.entries.push(e), t.latency = Math.max(t.latency, e.duration);
                else {
                    var r = {
                        id: e.interactionId,
                        latency: e.duration,
                        entries: [e]
                    };
                    _[r.id] = r, H.push(r)
                }
                H.sort((function (e, n) {
                    return n.latency - e.latency
                })), H.splice(10).forEach((function (e) {
                    delete _[e.id]
                }))
            }
        },
        j = {},
        z = function e(n) {
            document.prerendering ? T((function () {
                return e(n)
            })) : "complete" !== document.readyState ? addEventListener("load", (function () {
                return e(n)
            }), !0) : setTimeout(n, 0)
        },
        N = function () {
            var e = window.location;
            return {
                protocol: e.protocol,
                hostname: e.hostname,
                pathname: e.pathname
            }
        },
        G = function () {
            var e, n, t = window.navigator.userAgent,
                r = (null === (n = null === (e = window.navigator) || void 0 === e ? void 0 : e.userAgentData) || void 0 === n ? void 0 : n.platform) || window.navigator.platform;
            return ["Macintosh", "MacIntel", "MacPPC", "Mac68K", "MacOS"].some((function (e) {
                return e.toLowerCase() === r.toLowerCase()
            })) ? "Mac OS" : ["iPhone", "iPad", "iPod"].some((function (e) {
                return e.toLowerCase() === r.toLowerCase()
            })) ? "iOS" : ["Win32", "Win64", "Windows", "WinCE"].some((function (e) {
                return e.toLowerCase() === r.toLowerCase()
            })) ? "Windows" : /Android/.test(t) ? "Android" : /Linux/.test(r) ? "Linux" : "Other"
        },
        J = function () {
            var e = window.screen;
            return {
                width: e.availWidth,
                height: e.availHeight
            }
        },
        K = [],
        Q = function () {
            if (K.length > 0) {
                var e = (null == (t = document.querySelector("#netlify-rum-container")) ? void 0 : t.getAttribute("data-netlify-cwv-token")) || "",
                    n = K[0];
                K.slice(1).forEach((function (e) {
                    var t;
                    (t = n.events).push.apply(t, e.events)
                })), fetch("https://ingesteer.services-prod.nsvcs.net/rum_collection", {
                    method: "POST",
                    keepalive: !0,
                    mode: "cors",
                    headers: {
                        Authorization: "Bearer ".concat(e),
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(n)
                }), K = []
            }
            var t
        },
        U = function () {
            return U = Object.assign || function (e) {
                for (var n, t = 1, r = arguments.length; t < r; t++)
                    for (var i in n = arguments[t]) Object.prototype.hasOwnProperty.call(n, i) && (e[i] = n[i]);
                return e
            }, U.apply(this, arguments)
        },
        V = function (e, n) {
            return function (e, n, t, r) {
                return new(t || (t = Promise))((function (i, o) {
                    function a(e) {
                        try {
                            u(r.next(e))
                        } catch (e) {
                            o(e)
                        }
                    }

                    function c(e) {
                        try {
                            u(r.throw(e))
                        } catch (e) {
                            o(e)
                        }
                    }

                    function u(e) {
                        var n;
                        e.done ? i(e.value) : (n = e.value, n instanceof t ? n : new t((function (e) {
                            e(n)
                        }))).then(a, c)
                    }
                    u((r = r.apply(e, n || [])).next())
                }))
            }(void 0, void 0, void 0, (function () {
                var t, r, i;
                return function (e, n) {
                    var t, r, i, o, a = {
                        label: 0,
                        sent: function () {
                            if (1 & i[0]) throw i[1];
                            return i[1]
                        },
                        trys: [],
                        ops: []
                    };
                    return o = {
                        next: c(0),
                        throw: c(1),
                        return: c(2)
                    }, "function" == typeof Symbol && (o[Symbol.iterator] = function () {
                        return this
                    }), o;

                    function c(c) {
                        return function (u) {
                            return function (c) {
                                if (t) throw new TypeError("Generator is already executing.");
                                for (; o && (o = 0, c[0] && (a = 0)), a;) try {
                                    if (t = 1, r && (i = 2 & c[0] ? r.return : c[0] ? r.throw || ((i = r.return) && i.call(r), 0) : r.next) && !(i = i.call(r, c[1])).done) return i;
                                    switch (r = 0, i && (c = [2 & c[0], i.value]), c[0]) {
                                        case 0:
                                        case 1:
                                            i = c;
                                            break;
                                        case 4:
                                            return a.label++, {
                                                value: c[1],
                                                done: !1
                                            };
                                        case 5:
                                            a.label++, r = c[1], c = [0];
                                            continue;
                                        case 7:
                                            c = a.ops.pop(), a.trys.pop();
                                            continue;
                                        default:
                                            if (!((i = (i = a.trys).length > 0 && i[i.length - 1]) || 6 !== c[0] && 2 !== c[0])) {
                                                a = 0;
                                                continue
                                            }
                                            if (3 === c[0] && (!i || c[1] > i[0] && c[1] < i[3])) {
                                                a.label = c[1];
                                                break
                                            }
                                            if (6 === c[0] && a.label < i[1]) {
                                                a.label = i[1], i = c;
                                                break
                                            }
                                            if (i && a.label < i[2]) {
                                                a.label = i[2], a.ops.push(c);
                                                break
                                            }
                                            i[2] && a.ops.pop(), a.trys.pop();
                                            continue
                                    }
                                    c = n.call(e, a)
                                } catch (e) {
                                    c = [6, e], r = 0
                                } finally {
                                    t = i = 0
                                }
                                if (5 & c[0]) throw c[1];
                                return {
                                    value: c[0] ? c[1] : void 0,
                                    done: !0
                                }
                            }([c, u])
                        }
                    }
                }(this, (function (o) {
                    var a, c;
                    return t = {
                            type: e,
                            value: n
                        }, a = document.querySelector("#netlify-rum-container"), r = {
                            site_id: (null == a ? void 0 : a.getAttribute("data-netlify-rum-site-id")) || "",
                            branch: (null == a ? void 0 : a.getAttribute("data-netlify-deploy-branch")) || "",
                            context: (null == a ? void 0 : a.getAttribute("data-netlify-deploy-context")) || ""
                        }, i = function (e) {
                            var n = e.browser,
                                t = e.device,
                                r = e.location,
                                i = e.os,
                                o = e.size,
                                a = e.events;
                            return U(U({}, e.netlifyData), {
                                timestamp_ms: Date.now(),
                                request: {
                                    hostname: r.hostname,
                                    path: r.pathname
                                },
                                client: {
                                    browser: n,
                                    os: i,
                                    size: o,
                                    device: t
                                },
                                events: a
                            })
                        }({
                            browser: (c = window.navigator.userAgent, c.match(/chrome|chromium|crios/i) ? "Chrome" : c.match(/firefox|fxios/i) ? "Firefox" : c.match(/safari/i) ? "Safari" : c.match(/opr\//i) ? "Opera" : c.match(/edg/i) ? "Edge" : "Other"),
                            device: Math.min(window.screen.availWidth, window.screen.availHeight) < 768 || navigator.userAgent.indexOf("Mobi") > -1 ? "mobile" : "desktop",
                            location: N(),
                            netlifyData: r,
                            os: G(),
                            size: J(),
                            events: [t]
                        }),
                        function (e) {
                            K.push(e)
                        }(i), [2]
                }))
            }))
        };
    window === window.top && (V("performance", (o = performance.getEntriesByType("navigation")[0]) ? {
        ssl: o.requestStart - o.secureConnectionStart,
        dns: o.domainLookupEnd - o.domainLookupStart,
        tcp: o.connectEnd - o.connectStart,
        page_load: o.loadEventStart - o.fetchStart,
        ttfb: o.responseStart,
        server_timing: o.serverTiming
    } : {}), [function (e, n) {
        n = n || {}, S(h((function () {
            var t, r = [.1, .25],
                i = f("CLS", 0),
                o = 0,
                a = [],
                u = function (e) {
                    e.forEach((function (e) {
                        if (!e.hadRecentInput) {
                            var n = a[0],
                                t = a[a.length - 1];
                            o && e.startTime - t.startTime < 1e3 && e.startTime - n.startTime < 5e3 ? (o += e.value, a.push(e)) : (o = e.value, a = [e])
                        }
                    })), o > i.value && (i.value = o, i.entries = a, t())
                },
                s = d("layout-shift", u);
            s && (t = l(e, i, r, n.reportAllChanges), p((function () {
                u(s.takeRecords()), t(!0)
            })), c((function () {
                o = 0, i = f("CLS", 0), t = l(e, i, r, n.reportAllChanges), v((function () {
                    return t()
                }))
            })), setTimeout(t, 0))
        })))
    }, function (t, i) {
        i = i || {}, T((function () {
            var o, a = [100, 300],
                u = E(),
                s = f("FID"),
                v = function (e) {
                    e.startTime < u.firstHiddenTime && (s.value = e.processingStart - e.startTime, s.entries.push(e), o(!0))
                },
                m = function (e) {
                    e.forEach(v)
                },
                g = d("first-input", m);
            o = l(t, s, a, i.reportAllChanges), g && p(h((function () {
                m(g.takeRecords()), g.disconnect()
            }))), g && c((function () {
                var c;
                s = f("FID"), o = l(t, s, a, i.reportAllChanges), r = [], n = -1, e = null, k(addEventListener), c = v, r.push(c), x()
            }))
        }))
    }, S, function (e, n) {
        n = n || {}, T((function () {
            var t, r = [2500, 4e3],
                i = E(),
                o = f("LCP"),
                a = function (e) {
                    var n = e[e.length - 1];
                    if (n) {
                        var r = Math.max(n.startTime - s(), 0);
                        r < i.firstHiddenTime && (o.value = r, o.entries = [n], t())
                    }
                },
                u = d("largest-contentful-paint", a);
            if (u) {
                t = l(e, o, r, n.reportAllChanges);
                var m = h((function () {
                    j[o.id] || (a(u.takeRecords()), u.disconnect(), j[o.id] = !0, t(!0))
                }));
                ["keydown", "click"].forEach((function (e) {
                    addEventListener(e, m, !0)
                })), p(m), c((function (i) {
                    o = f("LCP"), t = l(e, o, r, n.reportAllChanges), v((function () {
                        o.value = performance.now() - i.timeStamp, j[o.id] = !0, t(!0)
                    }))
                }))
            }
        }))
    }, function (e, n) {
        n = n || {};
        var t = [800, 1800],
            r = f("TTFB"),
            i = l(e, r, t, n.reportAllChanges);
        z((function () {
            var o = u();
            if (o) {
                var a = o.responseStart;
                if (a <= 0 || a > performance.now()) return;
                r.value = Math.max(a - s(), 0), r.entries = [o], i(!0), c((function () {
                    r = f("TTFB", 0), (i = l(e, r, t, n.reportAllChanges))(!0)
                }))
            }
        }))
    }, function (e, n) {
        n = n || {}, T((function () {
            var t = [200, 500];
            W();
            var r, i = f("INP"),
                o = function (e) {
                    e.forEach((function (e) {
                        e.interactionId && R(e), "first-input" === e.entryType && !H.some((function (n) {
                            return n.entries.some((function (n) {
                                return e.duration === n.duration && e.startTime === n.startTime
                            }))
                        })) && R(e)
                    }));
                    var n, t = (n = Math.min(H.length - 1, Math.floor(B() / 50)), H[n]);
                    t && t.latency !== i.value && (i.value = t.latency, i.entries = t.entries, r())
                },
                a = d("event", o, {
                    durationThreshold: n.durationThreshold || 40
                });
            r = l(e, i, t, n.reportAllChanges), a && (a.observe({
                type: "first-input",
                buffered: !0
            }), p((function () {
                o(a.takeRecords()), i.value < 0 && B() > 0 && (i.value = 0, i.entries = []), r(!0)
            })), c((function () {
                H = [], q = F(), i = f("INP"), r = l(e, i, t, n.reportAllChanges)
            })))
        }))
    }].forEach((function (e) {
        e((function (e) {
            var n = e.name,
                t = e.value,
                r = e.rating;
            return V("web-vital", {
                name: n,
                value: t,
                rating: r
            })
        }))
    }))), addEventListener("visibilitychange", (function () {
        "hidden" === document.visibilityState && Q()
    })), addEventListener("pagehide", Q)
})();