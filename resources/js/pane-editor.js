export default function paneEditor(initial) {
    return {
        previewText: initial.text ?? '',
        colour: initial.colour ?? '',
        bgColour: initial.bgColour ?? '',
        size: initial.size ?? '',
        font: initial.font ?? '',
        top: initial.top ?? 0,
        left: initial.left ?? 0,
        width: initial.width ?? 0,
        height: initial.height ?? 0,
        scale: 0.5,
        dragging: false,
        resizing: false,
        _startX: 0,
        _startY: 0,
        _startTop: 0,
        _startLeft: 0,
        _startWidth: 0,
        _startHeight: 0,

        get paneStyle() {
            let s = `top: ${this.top}px; left: ${this.left}px; `;
            if (this.colour) s += `color: ${this.colour}; `;
            if (this.bgColour) s += `background-color: ${this.bgColour}; `;
            if (this.size) s += `font-size: ${this.size}; `;
            if (this.width > 0) s += `width: ${this.width}px; `;
            if (this.height > 0) s += `height: ${this.height}px; `;
            if (this.font) s += `font-family: '${this.font}', sans-serif; `;
            return s;
        },

        get positionReadout() {
            let s = `${this.left}, ${this.top}`;
            if (this.width > 0 || this.height > 0) {
                s += ` — ${this.width > 0 ? this.width + 'w' : ''}${this.width > 0 && this.height > 0 ? ' ' : ''}${this.height > 0 ? this.height + 'h' : ''}`;
            }
            return s;
        },

        init() {
            this.$nextTick(() => this.updateScale());
            window.addEventListener('resize', () => this.updateScale());
        },

        updateScale() {
            if (this.$refs.canvas) {
                this.scale = this.$refs.canvas.offsetWidth / 1920;
            }
        },

        startDrag(e) {
            this.dragging = true;
            this._startX = e.clientX;
            this._startY = e.clientY;
            this._startTop = this.top;
            this._startLeft = this.left;
        },

        startResize(e) {
            this.resizing = true;
            this._startX = e.clientX;
            this._startY = e.clientY;
            const el = this.$refs.paneEl;
            this._startWidth = this.width > 0 ? this.width : Math.round(el.offsetWidth / this.scale);
            this._startHeight = this.height > 0 ? this.height : Math.round(el.offsetHeight / this.scale);
        },

        onMouseMove(e) {
            const s = this.scale;
            if (this.dragging) {
                this.top = Math.max(0, Math.round(this._startTop + (e.clientY - this._startY) / s));
                this.left = Math.max(0, Math.round(this._startLeft + (e.clientX - this._startX) / s));
            } else if (this.resizing) {
                this.width = Math.max(1, Math.round(this._startWidth + (e.clientX - this._startX) / s));
                this.height = Math.max(1, Math.round(this._startHeight + (e.clientY - this._startY) / s));
            }
        },

        onMouseUp() {
            this.dragging = false;
            this.resizing = false;
        },
    };
}
