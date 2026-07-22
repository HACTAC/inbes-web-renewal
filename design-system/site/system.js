const iconSpriteUrl = "./assets/icons/iconoir.svg";

document.querySelectorAll("[data-icon]").forEach((placeholder) => {
  const svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
  const use = document.createElementNS("http://www.w3.org/2000/svg", "use");

  svg.classList.add("icon", ...placeholder.classList);
  svg.setAttribute("aria-hidden", "true");
  svg.setAttribute("viewBox", "0 0 24 24");
  use.setAttribute("href", `${iconSpriteUrl}#${placeholder.dataset.icon}`);
  svg.append(use);
  placeholder.replaceWith(svg);
});

const copyText = async (value) => {
  if (navigator.clipboard?.writeText) {
    await navigator.clipboard.writeText(value);
    return;
  }

  const input = document.createElement("textarea");
  input.value = value;
  input.setAttribute("readonly", "");
  input.style.position = "fixed";
  input.style.opacity = "0";
  document.body.append(input);
  input.select();
  document.execCommand("copy");
  input.remove();
};

document.querySelectorAll("[data-copy]").forEach((swatch) => {
  swatch.addEventListener("click", async () => {
    try {
      await copyText(swatch.dataset.copy);
      swatch.classList.add("is-copied");
      window.setTimeout(() => swatch.classList.remove("is-copied"), 1400);
    } catch {
      swatch.setAttribute("title", "コピーできませんでした");
    }
  });
});

const modal = document.querySelector("#demo-modal");
const modalOpeners = document.querySelectorAll("[data-open-modal]");
const modalClosers = document.querySelectorAll("[data-close-modal]");

modalOpeners.forEach((opener) => {
  opener.addEventListener("click", () => modal?.showModal());
});

modalClosers.forEach((closer) => {
  closer.addEventListener("click", () => modal?.close());
});

modal?.addEventListener("click", (event) => {
  if (event.target === modal) modal.close();
});

const drawer = document.querySelector("#demo-drawer");
const drawerBackdrop = document.querySelector(".drawer-backdrop");
const drawerOpeners = document.querySelectorAll("[data-open-drawer]");
const drawerClosers = document.querySelectorAll("[data-close-drawer]");
let drawerTrigger = null;

const drawerFocusable = () =>
  [...drawer.querySelectorAll("a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex='-1'])")];

const closeDrawer = () => {
  drawer.classList.remove("is-open");
  drawer.setAttribute("aria-hidden", "true");
  drawerBackdrop.hidden = true;
  document.body.classList.remove("is-locked");
  drawerTrigger?.focus();
};

const openDrawer = (trigger) => {
  drawerTrigger = trigger;
  drawerBackdrop.hidden = false;
  drawer.setAttribute("aria-hidden", "false");
  document.body.classList.add("is-locked");
  requestAnimationFrame(() => {
    drawer.classList.add("is-open");
    (drawerFocusable()[0] || drawer).focus();
  });
};

drawerOpeners.forEach((opener) => {
  opener.addEventListener("click", () => openDrawer(opener));
});

drawerClosers.forEach((closer) => {
  closer.addEventListener("click", closeDrawer);
});

drawer?.addEventListener("keydown", (event) => {
  if (event.key === "Escape") {
    closeDrawer();
    return;
  }

  if (event.key !== "Tab") return;

  const focusable = drawerFocusable();
  const first = focusable[0];
  const last = focusable[focusable.length - 1];

  if (!first || !last) {
    event.preventDefault();
    drawer.focus();
    return;
  }

  if (event.shiftKey && document.activeElement === first) {
    event.preventDefault();
    last.focus();
  } else if (!event.shiftKey && document.activeElement === last) {
    event.preventDefault();
    first.focus();
  }
});

document.querySelectorAll("form").forEach((form) => {
  form.addEventListener("submit", (event) => event.preventDefault());
});

const sectionLinks = new Map(
  [...document.querySelectorAll(".system-nav a")].map((link) => [link.getAttribute("href").slice(1), link]),
);

const sectionObserver = new IntersectionObserver(
  (entries) => {
    const visible = entries
      .filter((entry) => entry.isIntersecting)
      .sort((a, b) => b.intersectionRatio - a.intersectionRatio)[0];

    if (!visible) return;

    sectionLinks.forEach((link, sectionId) => {
      const isCurrent = sectionId === visible.target.id;
      link.classList.toggle("is-current", isCurrent);
      if (isCurrent) link.setAttribute("aria-current", "location");
      else link.removeAttribute("aria-current");
    });
  },
  { rootMargin: "-20% 0px -65%", threshold: [0, 0.2, 0.5] },
);

sectionLinks.forEach((_, sectionId) => {
  const section = document.getElementById(sectionId);
  if (section) sectionObserver.observe(section);
});
