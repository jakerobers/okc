import React from 'react';
import styled from "styled-components";
import {Link} from "react-router-dom";

const Row = styled.div`
  margin: 10px 5px;
`;

export function Home({ units }) {
  return (
    <div>
      {units.length === 0 && <div>No units available</div>}

      {units.map(e => {
        return (
          <Link to={`/unit/${e.id}`} key={e.id}>
            <Row>{e.address}</Row>
          </Link>
        );
      })}
    </div>
  );
}

