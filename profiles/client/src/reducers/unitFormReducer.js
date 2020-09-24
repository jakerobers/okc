export const initialState = {
  id: "",
  address: "",
  sqft: "",
  bed: "",
  bath: "",
  dryerHookup: "",
  hvacDetails: "",
  parking: "",
  rentAmount: "",
  securityDepositAmount: "",
  dogRentFee: "",
  catRentFee: "",
  birdRentFee: "",
  totalNumberOfPetsAllowed: "",
  petPolicy: "",
  electricPaidBy: "",
  heatPaidBy: "",
  waterSewerPaidBy: "",
  hotWaterPaidBy: "",
  lawncareResponsibility: "",
  snowRemovalResponsibility: "",
  garageCode: "",
  furnaceFilterSize: "",
  createdAt: "",
  updatedAt: "",
};


export function reducer(state, action) {
  switch (action.type) {
    case "UNIT:SET":
      return action.form;
    case "UNIT:SET_FIELD":
      const { key, value } = action;
      return {
        ...state,
        [key]: value
      };
    default:
      return state;
  }
}


