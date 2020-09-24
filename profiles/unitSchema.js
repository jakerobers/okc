const Joi = require('@hapi/joi')

module.exports = Joi.object().keys({
  address: Joi.string(),
  sqft: Joi.string(),
  bed: Joi.string(),
  bath: Joi.string(),
  dryerHookup: Joi.string(), // gas or electric
  hvacDetails: Joi.string(), // central heat/air?
  parking: Joi.string(), // street/garage, etc.
  rentAmount: Joi.string(),
  securityDepositAmount: Joi.string(),
  dogRentFee: Joi.string(),
  catRentFee: Joi.string(),
  birdRentFee: Joi.string(),
  totalNumberOfPetsAllowed: Joi.string(),
  petPolicy: Joi.string(),
  electricPaidBy: Joi.string(),
  heatPaidBy: Joi.string(),
  waterSewerPaidBy: Joi.string(),
  hotWaterPaidBy: Joi.string(),
  lawncareResponsibility: Joi.string(),
  snowRemovalResponsibility: Joi.string(),
  garageCode: Joi.string(),
  furnaceFilterSize: Joi.string(),
  createdAt: Joi.date().required(),
  updatedAt: Joi.date().required(),
})

