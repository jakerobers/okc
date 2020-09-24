import React, { useReducer, useState, useEffect, useCallback } from "react";
import { useParams } from "react-router-dom";
import styled from "styled-components";
import { omit } from "lodash";

import withSaveUnit from "../hooks/withSaveUnit";
import {
  reducer as unitReducer,
  initialState as initialUnitState
} from "../reducers/unitFormReducer";

const unitTranslations = {
  dryerHookup: "Dryer hookup type",
  hvacDetails: "HVAC type",
  parking: "Available parking",
  rentAmount: "Rent",
  securityDepositAmount: "Security deposit",
  dogRentFee: "Dog rent fee",
  catRentFee: "Cat rent fee",
  birdRentFee: "Bird rent fee",
  totalNumberOfPetsAllowed: "Total number of allowed pets",
  petPolicy: "Pet policy",
  electricPaidBy: "Electric bill paid by",
  heatPaidBy: "Heat bill paid by",
  waterSewerPaidBy: "Water/Sewer bill paid by",
  hotWaterPaidBy: "Hot water paid by",
  lawncareResponsibility: "Lawncare responsibility",
  snowRemovalResponsibility: "Snow removal responsibility",
  garageCode: "Garage Code",
  furnaceFilterSize: "Furnace filter size",
  createdAt: "Created at",
  updatedAt: "Updated at"
};

const generalFields = [
  "dryerHookup",
  "hvacDetails",
  "parking",
  "rentAmount",
  "securityDepositAmount",
  "dogRentFee",
  "catRentFee",
  "birdRentFee",
  "totalNumberOfPetsAllowed",
  "petPolicy",
  "electricPaidBy",
  "heatPaidBy",
  "waterSewerPaidBy",
  "hotWaterPaidBy",
  "lawncareResponsibility",
  "snowRemovalResponsibility"
];

const internalFields = [
  "garageCode",
  "furnaceFilterSize",
  "createdAt",
  "updatedAt"
];

const StyledTable = styled.table`
  margin-top: 30px;
`;

const NormalTr = styled.tr`
  background: white;
`;

const ShadedTr = styled.tr`
  background: #ccc;
`;

export function ShowUnit({ units, refreshUnits }) {
  const { id } = useParams();
  const [state, dispatch] = useReducer(unitReducer, initialUnitState);
  const [isLoading, save] = withSaveUnit(refreshUnits);

  const getUnit = useCallback(
    id => {
      return units.find(e => e.id === id);
    },
    [units]
  );

  useEffect(() => {
    const unit = getUnit(id);
    if (unit) {
      dispatch({
        type: "UNIT:SET",
        form: unit
      });
    }
  }, [id, getUnit]);

  return (
    <div>
      {isLoading && <div>Loading...</div>}
      <h2>{state.address}</h2>
      <h3>
        {state.bed} Bedroom -- {state.bath} Bathroom -- {state.sqft} SQFT
      </h3>

      <div>
        Dryer Hookups:<br />
        TODO: make checkbox
      </div>

      {generalFields.map((key, i) => {
        const RowComp = i % 2 === 0 ? NormalTr : ShadedTr;

        return (
          <div key={i}>
            <div>{unitTranslations[key]}</div>
            <div>
              <input
                type="text"
                value={state[key]}
                onChange={e => {
                  dispatch({
                    type: "UNIT:SET_FIELD",
                    key: key,
                    value: e.target.value
                  });
                }}
              />
            </div>
          </div>
        );
      })}

      <h1>Internal Fields</h1>
      <StyledTable>
        <tbody>
          {internalFields.map((key, i) => {
            const RowComp = i % 2 === 0 ? NormalTr : ShadedTr;

            return (
              <RowComp key={i}>
                <td>{unitTranslations[key]}</td>
                <td>
                  <input
                    type="text"
                    value={state[key]}
                    onChange={e => {
                      dispatch({
                        type: "UNIT:SET_FIELD",
                        key: key,
                        value: e.target.value
                      });
                    }}
                  />
                </td>
              </RowComp>
            );
          })}
        </tbody>
      </StyledTable>
      <button onClick={() => save(id, omit(state, "id"))}>Save</button>
    </div>
  );
}
