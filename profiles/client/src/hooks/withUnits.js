import { useEffect, useState } from "react";
import { getHost } from "../api";

const apiHost = getHost();

export default function () {
  const [units, setUnits] = useState([]);
  const [isLoaded, setLoaded] = useState(false);
  const [cacheCounter, setCacheCounter] = useState(0);

  function forceRefresh() {
    setCacheCounter(cacheCounter + 1);
  }

  useEffect(() => {
    fetch(`${apiHost}/units`).then(res => {
      return res.json().then(unitsResponse => {
        setLoaded(true);
        setUnits(unitsResponse);
      });
    });
  }, [cacheCounter]);

  return [units, isLoaded, forceRefresh];
}
