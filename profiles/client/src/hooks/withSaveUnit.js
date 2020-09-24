import { useState, useCallback } from 'react';

import {getHost} from '../api';

const apiHost = getHost();

export default function(onFinish) {
  const [isLoading, setLoading] = useState(false);

  const save = useCallback((id, body) => {
    setLoading(true);

    return fetch(`${apiHost}/unit/${id}`, {
      method: 'PUT',
      body: JSON.stringify(body),
      headers: {
        'Content-Type': 'application/json'
      },
    }).then(() => {
      setLoading(false);
      onFinish();
    }).catch(() => {
      setLoading(false);
    });
  }, [onFinish]);

  return [isLoading, save];
}


