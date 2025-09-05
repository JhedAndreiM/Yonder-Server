(() => {
  const members = {
    jun: {
      name: "Jun Vincent Guillermo",
      role: "Student",
      location: "Balanga City, Bataan",
      skills: [
        "Figma",
        "Adobe Photoshop",
        "Adobe Illustrator",
        "Canva",
        "UI/UX",
      ],
      tagline: "Pure grit, no shortcuts.",
      avatar: "imgs/junAvatar.jpg",
      color: "#e85a5a",
      pills: [
        { text: "Front-end Dev", variant: "" },
        { text: "UI/UX Designer", variant: "green" },
      ],
    },
    jhed: {
      name: "Jhed Andrei Magdato",
      role: "Full-stack Dev",
      location: "",
      skills: [
        "Teamwork",
        "Coding",
        "Creativity",
        "Communication",
        "Adaptability",
      ],
      tagline: "Sometimes it’s not how good you are, but how bad you want it.",
      avatar: "imgs/jhedAvatar.jpg",
      color: "pink",
      pills: [{ text: "Full-stack Dev", variant: "" }],
    },
    kristel: {
      name: "Kristel Joy Bagtas",
      role: "Quality Assurance, Documentator",
      location: "",
      skills: [
        "Attention to Detail",
        "Organizational Skills",
        "Analytical Thinking",
        "Technical Proficiency",
        "Communication Skills",
      ],
      tagline: "Life’s a puzzle, you’ll eventually figure it all out.",
      avatar: "imgs/kristelAvatar.jpg",
      color: "#ffb703",
      pills: [
        { text: "Quality Assurance", variant: "" },
        { text: "Documentator", variant: "green" },
      ],
    },
    iris: {
      name: "Iris Jewel Dinglas",
      role: "System Analyst, Project Manager",
      location: "",
      skills: [
        "Leadership",
        "Creativity",
        "Conflict Resolution",
        "Public Speaking",
        "Attention to Detail",
      ],
      tagline: "I’ll see you at the top—because the bottom is too crowded.",
      avatar: "imgs/irisAvatar.jpg",
      color: "#8338ec",
      pills: [
        { text: "System Analyst", variant: "" },
        { text: "Project Manager", variant: "green" },
      ],
    },
  };

  const section = document.querySelector("#section-4");
  if (!section) return;

  const gallery = section.querySelector(".team-gallery");
  const cards = Array.from(gallery.querySelectorAll(".team-box"));

  // Profile elements
  const profile = section.querySelector(".team-profile");
  const nameEl = profile.querySelector(".profile-name");
  const roleEl = profile.querySelector(".profile-role");
  const quoteEl = profile.querySelector(".profile-quote");
  const avatarEl = profile.querySelector(".profile-avatar");
  const skillsWrap = profile.querySelector(".skill-tags");
  const headerEl = profile.querySelector(".profile-header");
  const pillsWrap = profile.querySelector(".role-pills");

  function renderProfile(m) {
    if (!m) return;

    // Name, role, location
    nameEl.textContent = m.name || "";
    roleEl.innerHTML = `${m.role || ""}${
      m.location ? "<br>" + m.location : ""
    }`;

    // Tagline
    quoteEl.textContent = m.tagline ? `“${m.tagline}”` : "";

    // Skills
    skillsWrap.innerHTML = "";
    (m.skills || []).forEach((s) => {
      const tag = document.createElement("span");
      tag.className = "tag";
      tag.textContent = s;
      skillsWrap.appendChild(tag);
    });

    // Avatar
    if (m.avatar) {
      avatarEl.style.backgroundImage = `url('${m.avatar}')`;
    } else {
      avatarEl.style.backgroundImage = "";
    }

    // Header color
    if (m.color) {
      headerEl.style.backgroundColor = m.color;
    }

    // Pills
    pillsWrap.innerHTML = "";
    (m.pills || []).forEach((p) => {
      const pill = document.createElement("span");
      pill.className = "pill" + (p.variant === "green" ? " pill--green" : "");
      pill.textContent = p.text;
      pillsWrap.appendChild(pill);
    });
  }

  // Ensure Jun is featured by default
  cards.forEach((c) => c.classList.remove("featured"));
  const defaultCard = cards.find((c) => c.dataset.member === "jun");
  if (defaultCard) defaultCard.classList.add("featured");

  // Render Jun's profile by default
  renderProfile(members.jun);

  // Hover → make card featured + update profile
  cards.forEach((card) => {
    card.addEventListener("mouseenter", () => {
      cards.forEach((c) => c.classList.remove("featured"));
      card.classList.add("featured");

      const key = card.dataset.member;
      if (key && members[key]) renderProfile(members[key]);
    });
  });
})();
