/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;
const { registerBlockStyle, unregisterBlockStyle } = wp.blocks;

registerBlockStyle( 'core/button', {
  name: 'cta',
  label: __( 'CTA' ),
  isDefault: true,
});

registerBlockStyle( 'core/button', {
  name: 'secondary',
  label: __( 'Secondary' ),
  isDefault: false,
});
