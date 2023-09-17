
module.exports = {
  plugins: [
    "stylelint-csstree-validator"
  ],
  rules: {
	"csstree/validator": {
      ignore: ["font-display"]
	},
  },
};
