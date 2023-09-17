// https://github.com/csstree/stylelint-validator
module.exports = (ctx) => ({
  plugins: {
    stylelint: {},
    autoprefixer: {},
    //cssnano: ctx.env === 'production' ? {} : false
  }
});
