let modalIndex = 0;

function openModal(index) {
    modalIndex = index;
    const modal = document.getElementById("featureModal");
    if (!modal || typeof Swiper === "undefined") return;

    const modalContent = modal.querySelector('.modal-content');
    if (!modalContent) return;

    modal.style.display = "flex";
    modalContent.style.opacity = 0;

    // Swiperを一度破棄
    if (window.mySwiper) {
        window.mySwiper.destroy(true, true);
        window.mySwiper = null;
    }

    // モーダルが表示された後にSwiper初期化
    setTimeout(() => {
        window.mySwiper = new Swiper(".mySwiper", {
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            initialSlide: modalIndex,
            on: {
                init: function () {
                    modalContent.style.opacity = 1;
                }
            }
        });
    }, 50);
}

function closeModal() {
    const modal = document.getElementById("featureModal");
    if (!modal) return;

    modal.style.display = "none";
    if (window.mySwiper) {
        window.mySwiper.destroy(true, true);
        window.mySwiper = null;
    }
}


function toggleAccordion(element) {
    const isOpen = element.classList.contains("open");
    const allTitles = document.querySelectorAll('.accordion-title');
    const allContents = document.querySelectorAll('.accordion-content');

    // 一旦すべて閉じる
    allTitles.forEach(title => title.classList.remove('open'));
    allContents.forEach(content => content.style.display = 'none');

    // 開いてなければ開く
    if (!isOpen) {
        element.classList.add('open');
        const content = element.nextElementSibling;
        content.style.display = 'block';
    }
}




document.addEventListener("DOMContentLoaded", function () {
    const siteHeader = document.querySelector(".ck-site-header");
    const menuButton = document.querySelector(".ck-header-menu");
    const headerNav = document.querySelector(".ck-header-nav");

    if (siteHeader && menuButton && headerNav) {
        const closeHeaderMenu = () => {
            siteHeader.classList.remove("is-menu-open");
            menuButton.setAttribute("aria-expanded", "false");
        };

        menuButton.addEventListener("click", function () {
            const isOpen = siteHeader.classList.toggle("is-menu-open");
            menuButton.setAttribute("aria-expanded", String(isOpen));
        });

        headerNav.querySelectorAll("a").forEach(function (link) {
            link.addEventListener("click", closeHeaderMenu);
        });

        document.addEventListener("click", function (event) {
            if (!siteHeader.classList.contains("is-menu-open")) return;
            if (siteHeader.contains(event.target)) return;
            closeHeaderMenu();
        });

        document.addEventListener("keydown", function (event) {
            if (event.key === "Escape") closeHeaderMenu();
        });
    }

    const featureCards = document.querySelectorAll(".ck-feature-grid--twelve a");
    const positionFeaturePreview = (card) => {
        const preview = card.querySelector(".ck-feature-preview");
        if (!preview) return;

        card.classList.remove("is-preview-up");

        const cardRect = card.getBoundingClientRect();
        const previewHeight = preview.offsetHeight;
        const gap = 16;
        const viewportPadding = 18;
        const spaceAbove = cardRect.top - viewportPadding;
        const spaceBelow = window.innerHeight - cardRect.bottom - viewportPadding;

        if (spaceBelow < previewHeight + gap && spaceAbove > spaceBelow) {
            card.classList.add("is-preview-up");
        }
    };

    featureCards.forEach(function (card) {
        card.addEventListener("mouseenter", function () {
            positionFeaturePreview(card);
        });

        card.addEventListener("focusin", function () {
            positionFeaturePreview(card);
        });
    });

    if (featureCards.length) {
        const modal = document.createElement("div");
        modal.className = "ck-feature-screen-modal";
        modal.setAttribute("aria-hidden", "true");
        modal.innerHTML = `
            <div class="ck-feature-screen-dialog" role="dialog" aria-modal="true" aria-label="機能画面プレビュー">
                <div class="ck-feature-screen-media">
                    <img src="" alt="">
                </div>
                <div class="ck-feature-screen-body">
                    <span></span>
                    <h3></h3>
                    <p></p>
                    <button class="ck-feature-screen-close" type="button">閉じる</button>
                </div>
            </div>
        `;
        document.body.appendChild(modal);

        const modalImage = modal.querySelector("img");
        const modalNumber = modal.querySelector(".ck-feature-screen-body span");
        const modalTitle = modal.querySelector(".ck-feature-screen-body h3");
        const modalText = modal.querySelector(".ck-feature-screen-body p");
        const closeButton = modal.querySelector(".ck-feature-screen-close");
        let activeFeatureIndex = 0;

        const featureItems = Array.from(featureCards).map(function (card, index) {
            const preview = card.querySelector(".ck-feature-preview");
            const image = preview ? preview.querySelector("img") : null;
            const text = preview ? preview.querySelector("span") : null;
            const title = card.querySelector("h3");
            card.dataset.featureNumber = String(index + 1).padStart(2, "0");
            const number = document.createElement("span");
            number.className = "ck-feature-card-number";
            number.textContent = String(index + 1).padStart(2, "0");
            card.insertBefore(number, card.firstChild);

            const action = document.createElement("span");
            action.className = "ck-feature-screen-button";
            action.setAttribute("role", "button");
            action.setAttribute("tabindex", "0");
            action.textContent = "画面を見る";
            card.appendChild(action);

            return {
                card,
                action,
                title: title ? title.textContent.replace(/\s+/g, " ").trim() : "",
                src: image ? image.getAttribute("src") : "",
                alt: image ? image.getAttribute("alt") : "",
                text: text ? text.textContent.replace(/\s+/g, " ").trim() : "",
                number: String(index + 1).padStart(2, "0")
            };
        });

        const featureGrid = document.querySelector(".ck-feature-grid--twelve");
        let featureMobileControls = null;

        if (featureGrid) {
            featureMobileControls = document.createElement("div");
            featureMobileControls.className = "ck-feature-mobile-controls";
            featureMobileControls.innerHTML = `
                <button type="button" data-feature-carousel-prev aria-label="前の機能へ">‹</button>
                <span>横にスライド</span>
                <button type="button" data-feature-carousel-next aria-label="次の機能へ">›</button>
            `;
            featureGrid.parentNode.insertBefore(featureMobileControls, featureGrid);
        }

        const setActiveFeatureCard = function (index) {
            featureItems.forEach(function (item, itemIndex) {
                const isActive = itemIndex === index;
                const isPrev = itemIndex === (index - 1 + featureItems.length) % featureItems.length;
                const isNext = itemIndex === (index + 1) % featureItems.length;

                item.card.classList.toggle("is-mobile-active", isActive);
                item.card.classList.toggle("is-mobile-neighbor", isPrev || isNext);
            });
        };

        const closestFeatureIndex = function () {
            if (!featureGrid) return 0;
            const gridRect = featureGrid.getBoundingClientRect();
            const gridCenter = gridRect.left + gridRect.width / 2;
            let closestIndex = 0;
            let closestDistance = Number.POSITIVE_INFINITY;

            featureItems.forEach(function (item, index) {
                const rect = item.card.getBoundingClientRect();
                const cardCenter = rect.left + rect.width / 2;
                const distance = Math.abs(cardCenter - gridCenter);

                if (distance < closestDistance) {
                    closestDistance = distance;
                    closestIndex = index;
                }
            });

            return closestIndex;
        };

        const scrollToFeatureCard = function (index) {
            const item = featureItems[index];
            if (!item) return;

            setActiveFeatureCard(index);
            item.card.scrollIntoView({
                behavior: "smooth",
                block: "nearest",
                inline: "center"
            });
        };

        const scrollFeatureLoop = function (direction) {
            const currentIndex = closestFeatureIndex();
            const nextIndex = Math.max(0, Math.min(featureItems.length - 1, currentIndex + direction));
            scrollToFeatureCard(nextIndex);
        };

        if (featureGrid) {
            let scrollTimer = null;
            setActiveFeatureCard(0);

            featureGrid.addEventListener("scroll", function () {
                window.clearTimeout(scrollTimer);
                scrollTimer = window.setTimeout(function () {
                    setActiveFeatureCard(closestFeatureIndex());
                }, 80);
            }, { passive: true });
        }

        if (featureMobileControls) {
            featureMobileControls.querySelector("[data-feature-carousel-prev]").addEventListener("click", function () {
                scrollFeatureLoop(-1);
            });

            featureMobileControls.querySelector("[data-feature-carousel-next]").addEventListener("click", function () {
                scrollFeatureLoop(1);
            });
        }

        const showFeatureScreen = function (index) {
            const item = featureItems[index];
            if (!item || !item.src) return;

            activeFeatureIndex = index;
            modalImage.src = item.src;
            modalImage.alt = item.alt || item.title;
            modalNumber.textContent = `${item.number} / ${String(featureItems.length).padStart(2, "0")}`;
            modalTitle.textContent = item.title;
            modalText.textContent = item.text;
            modal.classList.add("is-open");
            modal.setAttribute("aria-hidden", "false");
            document.documentElement.classList.add("ck-feature-modal-open");
            closeButton.focus();
        };

        const closeFeatureScreen = function () {
            modal.classList.remove("is-open");
            modal.setAttribute("aria-hidden", "true");
            document.documentElement.classList.remove("ck-feature-modal-open");
            featureItems[activeFeatureIndex]?.action.focus();
        };

        featureItems.forEach(function (item, index) {
            const open = function (event) {
                event.preventDefault();
                event.stopPropagation();
                showFeatureScreen(index);
            };

            item.action.addEventListener("click", open);
            item.action.addEventListener("keydown", function (event) {
                if (event.key !== "Enter" && event.key !== " ") return;
                open(event);
            });
        });

        closeButton.addEventListener("click", closeFeatureScreen);

        modal.addEventListener("click", function (event) {
            if (event.target === modal) closeFeatureScreen();
        });

        document.addEventListener("keydown", function (event) {
            if (!modal.classList.contains("is-open")) return;
            if (event.key === "Escape") closeFeatureScreen();
        });
    }

    const structureZoomTrigger = document.querySelector(".ck-structure-zoom-trigger");

    if (structureZoomTrigger) {
        const structureZoomMedia = window.matchMedia("(max-width: 760px)");
        const structureImage = structureZoomTrigger.querySelector("img");
        const viewer = document.createElement("div");
        viewer.className = "ck-structure-viewer";
        viewer.setAttribute("aria-hidden", "true");
        viewer.innerHTML = `
            <button class="ck-structure-viewer__close" type="button" aria-label="拡大表示を閉じる">×</button>
            <div class="ck-structure-viewer__stage" role="dialog" aria-modal="true" aria-label="厨房君のシステム構成図">
                <img src="" alt="">
            </div>
        `;
        document.body.appendChild(viewer);

        const closeButton = viewer.querySelector(".ck-structure-viewer__close");
        const stage = viewer.querySelector(".ck-structure-viewer__stage");
        const viewerImage = viewer.querySelector("img");
        let isDragging = false;
        let dragStartX = 0;
        let dragStartY = 0;
        let scrollStartLeft = 0;
        let scrollStartTop = 0;

        const syncStructureZoomState = function () {
            const isMobileZoom = structureZoomMedia.matches;
            structureZoomTrigger.disabled = !isMobileZoom;
            if (isMobileZoom) {
                structureZoomTrigger.setAttribute("aria-label", "厨房君のシステム構成図を拡大表示");
            } else {
                structureZoomTrigger.removeAttribute("aria-label");
            }
            if (!isMobileZoom && viewer.classList.contains("is-open")) {
                viewer.classList.remove("is-open");
                viewer.setAttribute("aria-hidden", "true");
                document.documentElement.classList.remove("ck-feature-modal-open");
            }
        };

        const centerStructureImage = function () {
            window.requestAnimationFrame(function () {
                stage.scrollLeft = Math.max(0, (stage.scrollWidth - stage.clientWidth) / 2);
                stage.scrollTop = Math.max(0, (stage.scrollHeight - stage.clientHeight) / 2);
            });
        };

        const openStructureViewer = function () {
            if (!structureZoomMedia.matches) return;
            if (!structureImage) return;
            viewerImage.src = structureImage.currentSrc || structureImage.src;
            viewerImage.alt = structureImage.alt || "";
            viewer.classList.add("is-open");
            viewer.setAttribute("aria-hidden", "false");
            document.documentElement.classList.add("ck-feature-modal-open");
            centerStructureImage();
            closeButton.focus();
        };

        const closeStructureViewer = function () {
            viewer.classList.remove("is-open");
            viewer.setAttribute("aria-hidden", "true");
            document.documentElement.classList.remove("ck-feature-modal-open");
            structureZoomTrigger.focus();
        };

        structureZoomTrigger.addEventListener("click", openStructureViewer);
        syncStructureZoomState();

        if (typeof structureZoomMedia.addEventListener === "function") {
            structureZoomMedia.addEventListener("change", syncStructureZoomState);
        } else if (typeof structureZoomMedia.addListener === "function") {
            structureZoomMedia.addListener(syncStructureZoomState);
        }

        closeButton.addEventListener("click", closeStructureViewer);

        viewer.addEventListener("click", function (event) {
            if (event.target === viewer) closeStructureViewer();
        });

        stage.addEventListener("pointerdown", function (event) {
            isDragging = true;
            dragStartX = event.clientX;
            dragStartY = event.clientY;
            scrollStartLeft = stage.scrollLeft;
            scrollStartTop = stage.scrollTop;
            stage.classList.add("is-dragging");
            stage.setPointerCapture(event.pointerId);
        });

        stage.addEventListener("pointermove", function (event) {
            if (!isDragging) return;
            event.preventDefault();
            stage.scrollLeft = scrollStartLeft - (event.clientX - dragStartX);
            stage.scrollTop = scrollStartTop - (event.clientY - dragStartY);
        });

        const stopStructureDrag = function (event) {
            if (!isDragging) return;
            isDragging = false;
            stage.classList.remove("is-dragging");
            if (stage.hasPointerCapture(event.pointerId)) {
                stage.releasePointerCapture(event.pointerId);
            }
        };

        stage.addEventListener("pointerup", stopStructureDrag);
        stage.addEventListener("pointercancel", stopStructureDrag);
        stage.addEventListener("pointerleave", stopStructureDrag);

        document.addEventListener("keydown", function (event) {
            if (!viewer.classList.contains("is-open")) return;
            if (event.key === "Escape") closeStructureViewer();
        });
    }

    const revealTargets = document.querySelectorAll([
        ".ck-home-section .ck-home-section-head",
        ".ck-proof-grid article",
        ".ck-problem-list article",
        ".ck-problem-impact",
        ".ck-product-insight article",
        ".ck-feature-grid--twelve > a",
        ".ck-split > *",
        ".ck-esl-impact--solution",
        ".ck-type-grid article",
        ".ck-faq-list .qa-box",
        ".ck-home-final .ck-home-container"
    ].join(","));

    if (revealTargets.length) {
        revealTargets.forEach(function (target, index) {
            target.classList.add("ck-reveal");
            target.style.setProperty("--ck-reveal-delay", `${Math.min(index % 6, 5) * 55}ms`);
        });

        const flowCards = document.querySelectorAll(".ck-flow-grid article");
        flowCards.forEach(function (card, index) {
            card.classList.add("ck-reveal");
            card.style.setProperty("--ck-reveal-delay", `${index * 130}ms`);
        });

        const eslSyncCards = document.querySelectorAll(".ck-esl-sync-flow article");
        eslSyncCards.forEach(function (card, index) {
            card.classList.add("ck-reveal");
            card.style.setProperty("--ck-reveal-delay", `${index * 140}ms`);
        });

        if ("IntersectionObserver" in window) {
            const revealObserver = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (!entry.isIntersecting) return;
                    entry.target.classList.add("is-visible");
                    revealObserver.unobserve(entry.target);
                });
            }, {
                threshold: 0.16,
                rootMargin: "0px 0px -8% 0px"
            });

            revealTargets.forEach(function (target) {
                revealObserver.observe(target);
            });

            const flowGrid = document.querySelector(".ck-flow-grid");
            if (flowGrid && flowCards.length) {
                const flowObserver = new IntersectionObserver(function (entries) {
                    entries.forEach(function (entry) {
                        if (!entry.isIntersecting) return;
                        flowCards.forEach(function (card) {
                            card.classList.add("is-visible");
                        });
                        flowObserver.unobserve(entry.target);
                    });
                }, {
                    threshold: 0.16,
                    rootMargin: "0px 0px -8% 0px"
                });

                flowObserver.observe(flowGrid);
            }

            const eslSyncFlow = document.querySelector(".ck-esl-sync-flow");
            if (eslSyncFlow && eslSyncCards.length) {
                const eslSyncObserver = new IntersectionObserver(function (entries) {
                    entries.forEach(function (entry) {
                        if (!entry.isIntersecting) return;
                        eslSyncCards.forEach(function (card) {
                            card.classList.add("is-visible");
                        });
                        eslSyncObserver.unobserve(entry.target);
                    });
                }, {
                    threshold: 0.16,
                    rootMargin: "0px 0px -8% 0px"
                });

                eslSyncObserver.observe(eslSyncFlow);
            }
        } else {
            revealTargets.forEach(function (target) {
                target.classList.add("is-visible");
            });
            document.querySelectorAll(".ck-flow-grid article").forEach(function (card) {
                card.classList.add("is-visible");
            });
            document.querySelectorAll(".ck-esl-sync-flow article").forEach(function (card) {
                card.classList.add("is-visible");
            });
        }
    }

    const qaBoxes = document.querySelectorAll(".qa-box");
    const closeQaBox = function (box) {
        const q = box.querySelector(".qa-q");
        box.classList.remove("active");
        if (q) q.setAttribute("aria-expanded", "false");
    };

    const openQaBox = function (box) {
        const q = box.querySelector(".qa-q");
        box.classList.add("active");
        if (q) q.setAttribute("aria-expanded", "true");
    };

    qaBoxes.forEach(function (box, index) {
        const q = box.querySelector(".qa-q");
        const a = box.querySelector(".qa-a");
        if (!q || !a) return;

        const panelId = `qa-panel-${index + 1}`;
        a.id = panelId;
        q.setAttribute("aria-controls", panelId);
        q.setAttribute("aria-expanded", "false");

        if (index === 0) {
            openQaBox(box);
        }

        q.addEventListener("click", function () {
            const shouldOpen = !box.classList.contains("active");
            qaBoxes.forEach(closeQaBox);
            if (shouldOpen) openQaBox(box);
        });
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const sections = document.querySelectorAll(".partner-intro.full-bg, .partner-intro2.full-bg, .fixed-p-full");

    sections.forEach(function (section, index) {
        section.classList.add(index % 2 === 0 ? "ck-section-light" : "ck-section-dark");
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const scrollTables = document.querySelectorAll(".ck-service-table-wrap");

    scrollTables.forEach(function (tableWrap) {
        if (tableWrap.scrollWidth <= tableWrap.clientWidth) {
            tableWrap.classList.add("is-scrolled");
            return;
        }

        tableWrap.addEventListener("scroll", function () {
            if (tableWrap.scrollLeft > 8) {
                tableWrap.classList.add("is-scrolled");
            }
        }, { passive: true });
    });
});



window.openModal = openModal;
window.closeModal = closeModal;
window.toggleAccordion = toggleAccordion;





