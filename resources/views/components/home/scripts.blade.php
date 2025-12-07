<script>
    function animateStats() {
        const targets = {
            downloads: 200,
            packages: 30,
            contributors: 2,
            stars: 75
        };
        const duration = 2000;
        const steps = 60;
        const stepDuration = duration / steps;

        Object.keys(targets).forEach(key => {
            let current = 0;
            const target = targets[key];
            const increment = target / steps;

            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                this.stats[key] = Math.floor(current);
            }, stepDuration);
        });
    }
</script>
