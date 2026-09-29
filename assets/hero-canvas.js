/**
 * AI Kapadokya · Cyber-Cappadocia Interactive Canvas Engine
 * Breathtaking synthesis of Cappadocia's mystical landscape & futuristic AI.
 * Features:
 *  - Floating AI Hot Air Balloons with glowing neural circuits & burner flames
 *  - Fairy Chimney (Peribacaları) cyber-horizon silhouette with telemetry beacons
 *  - Rising neural telemetry particles & constellation grid
 *  - Interactive mouse proximity & flame bursts
 *  - Dynamic Dark & Light mode color palette
 */
(() => {
  const canvas = document.getElementById('heroCanvas');
  if (!canvas) return;

  const ctx = canvas.getContext('2d');
  let width, height;
  let particles = [];
  let balloons = [];
  const mouse = { x: -1000, y: -1000, radius: 180 };

  const isReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (isReduced) return;

  // Resize handler
  const resize = () => {
    const rect = canvas.parentElement.getBoundingClientRect();
    width = canvas.width = rect.width;
    height = canvas.height = rect.height;
    initBalloons();
    initParticles();
  };

  // =========================================================================
  // 1. AI Hot Air Balloon Model
  // =========================================================================
  class AIBalloon {
    constructor(x, y, scale, speed, depth) {
      this.x = x;
      this.baseY = y;
      this.y = y;
      this.scale = scale;
      this.speed = speed;
      this.depth = depth; // 0: far, 1: mid, 2: near
      this.time = Math.random() * 100;
      this.flamePulse = Math.random() * 10;
      this.circuitOffset = Math.random() * Math.PI * 2;
      this.burnerTimer = Math.random() * 180;
      this.burnerActive = false;
      this.burnerIntensity = 0;
    }

    update() {
      this.time += 0.018;
      this.x += this.speed;

      // Wrap around screen
      const margin = 80 * this.scale;
      if (this.x > width + margin) {
        this.x = -margin;
        this.baseY = height * 0.12 + Math.random() * (height * 0.45);
      }

      // Vertical sinusoidal float (gentle bobbing)
      this.y = this.baseY + Math.sin(this.time * 0.8) * (8 * this.scale);

      // Periodic burner ignition
      this.burnerTimer++;
      if (this.burnerTimer > 240 + Math.random() * 100) {
        this.burnerActive = true;
        this.burnerTimer = 0;
      }
      if (this.burnerActive) {
        this.burnerIntensity = Math.min(1, this.burnerIntensity + 0.08);
        if (this.burnerIntensity >= 1) this.burnerActive = false;
      } else {
        this.burnerIntensity = Math.max(0, this.burnerIntensity - 0.03);
      }

      // Mouse proximity interaction (ignites burner & glows)
      const dx = mouse.x - this.x;
      const dy = mouse.y - this.y;
      const dist = Math.sqrt(dx * dx + dy * dy);
      if (dist < mouse.radius * this.scale * 1.5) {
        this.burnerIntensity = Math.max(this.burnerIntensity, (mouse.radius * this.scale * 1.5 - dist) / (mouse.radius * this.scale * 1.5));
      }
    }

    draw(isDark) {
      ctx.save();
      ctx.translate(this.x, this.y);
      ctx.scale(this.scale, this.scale);

      const radius = 24;
      const heightOffset = 34;

      // Envelope glow aura
      const auraGradient = ctx.createRadialGradient(0, -radius * 0.4, radius * 0.2, 0, -radius * 0.4, radius * 2.2);
      if (isDark) {
        auraGradient.addColorStop(0, `rgba(168, 85, 247, ${0.25 * (1 + this.burnerIntensity)})`);
        auraGradient.addColorStop(0.6, `rgba(124, 58, 237, ${0.08 * (1 + this.burnerIntensity)})`);
        auraGradient.addColorStop(1, 'rgba(124, 58, 237, 0)');
      } else {
        auraGradient.addColorStop(0, `rgba(109, 40, 217, ${0.18 * (1 + this.burnerIntensity)})`);
        auraGradient.addColorStop(0.6, `rgba(99, 102, 241, ${0.05 * (1 + this.burnerIntensity)})`);
        auraGradient.addColorStop(1, 'rgba(99, 102, 241, 0)');
      }
      ctx.fillStyle = auraGradient;
      ctx.beginPath();
      ctx.arc(0, -radius * 0.4, radius * 2.2, 0, Math.PI * 2);
      ctx.fill();

      // 1. Balloon Envelope Body (Inverted teardrop)
      ctx.beginPath();
      ctx.moveTo(-radius * 0.45, 10);
      ctx.bezierCurveTo(-radius * 1.15, 2, -radius * 1.25, -radius * 0.9, 0, -radius * 1.35);
      ctx.bezierCurveTo(radius * 1.25, -radius * 0.9, radius * 1.15, 2, radius * 0.45, 10);
      ctx.closePath();

      // Envelope Fill Gradient
      const envGrad = ctx.createLinearGradient(-radius, -radius * 1.35, radius, 10);
      if (isDark) {
        envGrad.addColorStop(0, '#2e1065');
        envGrad.addColorStop(0.4, '#4c1d95');
        envGrad.addColorStop(0.7, '#6d28d9');
        envGrad.addColorStop(1, '#1e1b4b');
      } else {
        envGrad.addColorStop(0, '#7c3aed');
        envGrad.addColorStop(0.5, '#6366f1');
        envGrad.addColorStop(1, '#4338ca');
      }
      ctx.fillStyle = envGrad;
      ctx.fill();

      // Envelope Border
      ctx.strokeStyle = isDark ? 'rgba(216, 180, 254, 0.45)' : 'rgba(255, 255, 255, 0.65)';
      ctx.lineWidth = 1.2;
      ctx.stroke();

      // 2. Neural Circuit Ribs & Nodes on the Balloon Envelope
      ctx.strokeStyle = isDark ? 'rgba(232, 121, 249, 0.6)' : 'rgba(255, 255, 255, 0.55)';
      ctx.lineWidth = 0.8;

      // Center longitudinal arc
      ctx.beginPath();
      ctx.moveTo(0, -radius * 1.35);
      ctx.lineTo(0, 10);
      ctx.stroke();

      // Left curved arc
      ctx.beginPath();
      ctx.moveTo(0, -radius * 1.35);
      ctx.quadraticCurveTo(-radius * 0.6, -radius * 0.5, -radius * 0.22, 10);
      ctx.stroke();

      // Right curved arc
      ctx.beginPath();
      ctx.moveTo(0, -radius * 1.35);
      ctx.quadraticCurveTo(radius * 0.6, -radius * 0.5, radius * 0.22, 10);
      ctx.stroke();

      // Cyber Neural Nodes (Flashing dots on envelope)
      const pulseTime = this.time * 2 + this.circuitOffset;
      const nodeAlpha = 0.5 + Math.sin(pulseTime) * 0.4;
      ctx.fillStyle = isDark
        ? `rgba(216, 255, 98, ${nodeAlpha})` // Lime tech accent
        : `rgba(255, 255, 255, ${nodeAlpha * 0.9})`;

      [
        { x: 0, y: -radius * 0.9 },
        { x: -radius * 0.45, y: -radius * 0.4 },
        { x: radius * 0.45, y: -radius * 0.4 },
        { x: -radius * 0.25, y: 0 },
        { x: radius * 0.25, y: 0 },
        { x: 0, y: -radius * 0.2 }
      ].forEach(pt => {
        ctx.beginPath();
        ctx.arc(pt.x, pt.y, 1.4, 0, Math.PI * 2);
        ctx.fill();
      });

      // 3. Burner & Flame Flare
      if (this.burnerIntensity > 0.05) {
        const flameHeight = 12 * this.burnerIntensity;
        ctx.beginPath();
        ctx.moveTo(-3, 11);
        ctx.quadraticCurveTo(0, 11 + flameHeight, 3, 11);
        ctx.closePath();

        const flameGrad = ctx.createLinearGradient(0, 11, 0, 11 + flameHeight);
        flameGrad.addColorStop(0, 'rgba(255, 255, 255, 0.95)');
        flameGrad.addColorStop(0.3, 'rgba(251, 191, 36, 0.9)'); // Warm amber
        flameGrad.addColorStop(0.7, 'rgba(239, 68, 68, 0.7)'); // Orange red
        flameGrad.addColorStop(1, 'rgba(168, 85, 247, 0)');
        ctx.fillStyle = flameGrad;
        ctx.fill();

        // Burner light ring
        ctx.beginPath();
        ctx.arc(0, 11, 6 * this.burnerIntensity, 0, Math.PI * 2);
        ctx.fillStyle = `rgba(251, 191, 36, ${0.4 * this.burnerIntensity})`;
        ctx.fill();
      }

      // 4. Suspension Ropes
      ctx.strokeStyle = isDark ? 'rgba(203, 213, 225, 0.45)' : 'rgba(71, 85, 105, 0.5)';
      ctx.lineWidth = 0.6;
      ctx.beginPath();
      ctx.moveTo(-radius * 0.35, 10);
      ctx.lineTo(-4, 18);
      ctx.moveTo(radius * 0.35, 10);
      ctx.lineTo(4, 18);
      ctx.moveTo(-radius * 0.15, 10);
      ctx.lineTo(-2, 18);
      ctx.moveTo(radius * 0.15, 10);
      ctx.lineTo(2, 18);
      ctx.stroke();

      // 5. High-Tech Basket
      ctx.fillStyle = isDark ? '#1e1b4b' : '#334155';
      ctx.strokeStyle = isDark ? 'rgba(216, 180, 254, 0.5)' : 'rgba(255, 255, 255, 0.6)';
      ctx.lineWidth = 0.8;
      ctx.beginPath();
      ctx.roundRect ? ctx.roundRect(-5, 18, 10, 6, 1.5) : ctx.rect(-5, 18, 10, 6);
      ctx.fill();
      ctx.stroke();

      // Basket status indicator LED
      ctx.fillStyle = isDark ? '#d8ff62' : '#38bdf8';
      ctx.beginPath();
      ctx.arc(0, 21, 1, 0, Math.PI * 2);
      ctx.fill();

      ctx.restore();
    }
  }

  // =========================================================================
  // 2. Fairy Chimney (Peribacaları) Cyber Skyline Silhouette
  // =========================================================================
  const drawFairyChimneys = (isDark) => {
    const groundY = height;
    ctx.save();

    // Backdrop Silhouette Gradient
    const silGrad = ctx.createLinearGradient(0, height - 120, 0, height);
    if (isDark) {
      silGrad.addColorStop(0, 'rgba(18, 11, 28, 0)');
      silGrad.addColorStop(0.5, 'rgba(26, 16, 42, 0.45)');
      silGrad.addColorStop(1, 'rgba(15, 8, 25, 0.85)');
    } else {
      silGrad.addColorStop(0, 'rgba(241, 245, 249, 0)');
      silGrad.addColorStop(0.5, 'rgba(226, 232, 240, 0.35)');
      silGrad.addColorStop(1, 'rgba(203, 213, 225, 0.65)');
    }

    ctx.fillStyle = silGrad;
    ctx.beginPath();
    ctx.moveTo(0, groundY);

    // Procedural organic fairy chimney contours
    const chimneys = [
      { xPct: 0.05, h: 55, w: 28, capW: 34, capH: 10 },
      { xPct: 0.12, h: 85, w: 34, capW: 42, capH: 14 },
      { xPct: 0.18, h: 45, w: 22, capW: 26, capH: 8 },
      { xPct: 0.28, h: 70, w: 30, capW: 38, capH: 12 },
      { xPct: 0.38, h: 105, w: 40, capW: 50, capH: 16 },
      { xPct: 0.48, h: 60, w: 26, capW: 32, capH: 10 },
      { xPct: 0.58, h: 90, w: 36, capW: 46, capH: 14 },
      { xPct: 0.68, h: 50, w: 24, capW: 30, capH: 9 },
      { xPct: 0.78, h: 110, w: 44, capW: 54, capH: 16 },
      { xPct: 0.88, h: 75, w: 32, capW: 40, capH: 12 },
      { xPct: 0.95, h: 65, w: 28, capW: 36, capH: 11 }
    ];

    chimneys.forEach((c) => {
      const cx = c.xPct * width;
      const topY = groundY - c.h;

      // Base taper up to neck
      ctx.lineTo(cx - c.w * 0.5, groundY);
      ctx.lineTo(cx - c.w * 0.28, topY + c.capH);

      // Hat / Mushroom Rock Cap
      ctx.bezierCurveTo(cx - c.capW * 0.5, topY + c.capH * 0.6, cx - c.capW * 0.4, topY, cx, topY);
      ctx.bezierCurveTo(cx + c.capW * 0.4, topY, cx + c.capW * 0.5, topY + c.capH * 0.6, cx + c.w * 0.28, topY + c.capH);

      ctx.lineTo(cx + c.w * 0.5, groundY);
    });

    ctx.lineTo(width, groundY);
    ctx.closePath();
    ctx.fill();

    // Cyber contour wireframe on fairy chimney peaks
    ctx.strokeStyle = isDark ? 'rgba(168, 85, 247, 0.22)' : 'rgba(109, 40, 217, 0.18)';
    ctx.lineWidth = 1;
    ctx.stroke();

    // Telemetry Beacon Dots on the top of major chimneys
    const now = Date.now() * 0.003;
    chimneys.forEach((c, idx) => {
      const cx = c.xPct * width;
      const topY = groundY - c.h;
      const pulse = Math.sin(now + idx * 1.3) * 0.5 + 0.5;

      ctx.beginPath();
      ctx.arc(cx, topY - 2, 2.2, 0, Math.PI * 2);
      ctx.fillStyle = isDark
        ? `rgba(216, 255, 98, ${0.4 + pulse * 0.6})`
        : `rgba(109, 40, 217, ${0.4 + pulse * 0.5})`;
      ctx.fill();

      // Subtle vertical beacon beam rising toward sky
      if (idx % 2 === 0) {
        ctx.beginPath();
        ctx.moveTo(cx, topY - 2);
        ctx.lineTo(cx, topY - 35);
        ctx.strokeStyle = isDark
          ? `rgba(216, 255, 98, ${0.15 * pulse})`
          : `rgba(109, 40, 217, ${0.12 * pulse})`;
        ctx.lineWidth = 0.8;
        ctx.stroke();
      }
    });

    ctx.restore();
  };

  // =========================================================================
  // 3. Neural Constellation & Data Telemetry Particles
  // =========================================================================
  class TelemetryParticle {
    constructor() {
      this.reset(true);
    }

    reset(initial = false) {
      this.x = Math.random() * width;
      this.y = initial ? Math.random() * height : height + 10;
      this.vx = (Math.random() - 0.5) * 0.4;
      this.vy = -(Math.random() * 0.6 + 0.25); // Gentle upward drift
      this.radius = Math.random() * 1.5 + 0.8;
      this.baseAlpha = Math.random() * 0.4 + 0.15;
      this.alpha = this.baseAlpha;
      this.isLime = Math.random() > 0.82; // Special accent
    }

    update() {
      this.x += this.vx;
      this.y += this.vy;

      if (this.y < -10 || this.x < -10 || this.x > width + 10) {
        this.reset();
      }

      // Proximity interaction with mouse
      const dx = mouse.x - this.x;
      const dy = mouse.y - this.y;
      const dist = Math.sqrt(dx * dx + dy * dy);

      if (dist < mouse.radius) {
        const force = (mouse.radius - dist) / mouse.radius;
        this.alpha = Math.min(0.9, this.baseAlpha + force * 0.6);
        this.x -= (dx / dist) * force * 1.2;
        this.y -= (dy / dist) * force * 1.2;
      } else {
        this.alpha += (this.baseAlpha - this.alpha) * 0.04;
      }
    }

    draw(isDark) {
      ctx.beginPath();
      ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
      if (this.isLime && isDark) {
        ctx.fillStyle = `rgba(216, 255, 98, ${this.alpha * 1.3})`;
      } else {
        ctx.fillStyle = isDark
          ? `rgba(168, 85, 247, ${this.alpha * 1.2})`
          : `rgba(99, 102, 241, ${this.alpha * 0.75})`;
      }
      ctx.fill();
    }
  }

  const initBalloons = () => {
    balloons = [
      // Far background balloons (small, slow, subtle)
      new AIBalloon(width * 0.08, height * 0.18, 0.42, 0.12, 0),
      new AIBalloon(width * 0.42, height * 0.26, 0.38, 0.10, 0),
      new AIBalloon(width * 0.82, height * 0.16, 0.48, 0.14, 0),

      // Mid-ground balloons
      new AIBalloon(width * 0.22, height * 0.38, 0.68, 0.18, 1),
      new AIBalloon(width * 0.68, height * 0.32, 0.62, 0.16, 1),

      // Near majestic foreground balloon
      new AIBalloon(width * 0.88, height * 0.42, 0.95, 0.22, 2)
    ];
  };

  const initParticles = () => {
    const count = Math.min(55, Math.floor((width * height) / 22000));
    particles = [];
    for (let i = 0; i < count; i++) {
      particles.push(new TelemetryParticle());
    }
  };

  const drawNeuralConnections = (isDark) => {
    const maxDist = 110;
    for (let i = 0; i < particles.length; i++) {
      for (let j = i + 1; j < particles.length; j++) {
        const dx = particles[i].x - particles[j].x;
        const dy = particles[i].y - particles[j].y;
        const dist = Math.sqrt(dx * dx + dy * dy);

        if (dist < maxDist) {
          const ratio = 1 - dist / maxDist;
          const alpha = isDark ? ratio * 0.14 : ratio * 0.08;
          ctx.beginPath();
          ctx.moveTo(particles[i].x, particles[i].y);
          ctx.lineTo(particles[j].x, particles[j].y);
          ctx.strokeStyle = isDark
            ? `rgba(168, 85, 247, ${alpha})`
            : `rgba(99, 102, 241, ${alpha})`;
          ctx.lineWidth = 0.7;
          ctx.stroke();
        }
      }
    }
  };

  // =========================================================================
  // 4. Main Animation Loop
  // =========================================================================
  const animate = () => {
    ctx.clearRect(0, 0, width, height);

    const isDark = document.documentElement.getAttribute('data-theme') !== 'light';

    // 1. Draw Fairy Chimney Skyline at Bottom
    drawFairyChimneys(isDark);

    // 2. Draw Neural Connections & Particles
    drawNeuralConnections(isDark);
    particles.forEach(p => {
      p.update();
      p.draw(isDark);
    });

    // 3. Draw and Update AI Hot Air Balloons
    balloons.forEach(b => {
      b.update();
      b.draw(isDark);
    });

    // 4. Draw Interactive Shockwave Bursts
    shockwaves.forEach((sw, i) => {
      sw.radius += sw.vr;
      sw.alpha -= 0.025;
      if (sw.alpha <= 0) {
        shockwaves.splice(i, 1);
        return;
      }
      ctx.save();
      ctx.beginPath();
      ctx.arc(sw.x, sw.y, sw.radius, 0, Math.PI * 2);
      ctx.strokeStyle = isDark
        ? `rgba(216, 255, 98, ${sw.alpha})`
        : `rgba(124, 58, 237, ${sw.alpha})`;
      ctx.lineWidth = 2;
      ctx.stroke();

      // Sparkles
      sw.sparks.forEach(sp => {
        sp.x += sp.vx;
        sp.y += sp.vy;
        ctx.beginPath();
        ctx.arc(sp.x, sp.y, sp.size, 0, Math.PI * 2);
        ctx.fillStyle = isDark
          ? `rgba(232, 121, 249, ${sw.alpha * 1.5})`
          : `rgba(109, 40, 217, ${sw.alpha * 1.2})`;
        ctx.fill();
      });
      ctx.restore();
    });

    requestAnimationFrame(animate);
  };

  const shockwaves = [];
  const triggerClickBurst = (x, y) => {
    const sparks = [];
    for (let i = 0; i < 24; i++) {
      const angle = Math.random() * Math.PI * 2;
      const speed = Math.random() * 4 + 1.5;
      sparks.push({
        x,
        y,
        vx: Math.cos(angle) * speed,
        vy: Math.sin(angle) * speed,
        size: Math.random() * 2 + 1
      });
    }
    shockwaves.push({ x, y, radius: 10, vr: 4.5, alpha: 1, sparks });

    // Flare nearby balloons
    balloons.forEach(b => {
      const dx = b.x - x;
      const dy = b.y - y;
      const dist = Math.sqrt(dx * dx + dy * dy);
      if (dist < 350) {
        b.burnerActive = true;
        b.burnerIntensity = 1;
      }
    });
  };

  window.addEventListener('resize', resize, { passive: true });
  canvas.parentElement.addEventListener('mousemove', (e) => {
    const rect = canvas.getBoundingClientRect();
    mouse.x = e.clientX - rect.left;
    mouse.y = e.clientY - rect.top;
  }, { passive: true });

  canvas.parentElement.addEventListener('click', (e) => {
    const rect = canvas.getBoundingClientRect();
    triggerClickBurst(e.clientX - rect.left, e.clientY - rect.top);
  });

  canvas.parentElement.addEventListener('mouseleave', () => {
    mouse.x = -1000;
    mouse.y = -1000;
  }, { passive: true });

  resize();
  animate();
})();
