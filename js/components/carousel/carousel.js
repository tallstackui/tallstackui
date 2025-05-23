export default (images, cover = 1, autoplay, interval, withoutLoop) => ({
    images: images,
    time: interval,
    current: cover,
    interval: null,
    paused: false,
    init() {
        if (autoplay) this.play();
    },
    /**
     * Start the carousel automation.
     *
     * @returns {void}
     */
    play() {
        this.interval = setInterval(() => {
            if (!this.paused) {
                this.next()
            }
        }, this.time)
    },
    /**
     * Reset the carousel automation.
     *
     * @returns {void}
     */
    reset() {
        if (!autoplay) return;

        clearInterval(this.interval)

        this.time = interval;

        this.play()
    },
    /**
     * Advance to the next carousel image.
     *
     * @returns {void}
     */
    next() {
        if (withoutLoop && this.current === this.images.length) {
            return;
        }

        if (this.current < this.images.length) {
            this.current = this.current + 1

            this.event('next');

            return;
        }

        this.current = 1

        this.event('next');
    },
    /**
     * Back to the previous carousel image.
     *
     * @returns {void}
     */
    previous() {
        if (withoutLoop && this.current === 1) {
            return;
        }

        if (this.current > 1) {
            this.current = this.current - 1

            this.event('previous');

            return;
        }

        this.current = this.images.length

        this.event('previous');
    },
    /**
     * Dispatch events.
     *
     * @param {String} type
     */
    event(type) {
        this.$refs.carousel.dispatchEvent(new CustomEvent(type, {
            detail : { current: this.current, image: this.images[this.current] }
        }))
    }
})
