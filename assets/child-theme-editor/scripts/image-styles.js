const { unregisterBlockStyle } = wp.blocks;

// Remove 'rounded' style.
wp.domReady( () => {
  unregisterBlockStyle('core/image', 'rounded');
});
