import React from "react";
import { BrowserRouter as Router, Switch, Route } from "react-router-dom";
import {
  ThemeProvider,
  DEFAULT_THEME
} from "@zendeskgarden/react-theming";

import withUnits from "./hooks/withUnits";
import "./App.css";
import { ShowUnit, Home } from "./pages";

function App() {
  const [units, isLoaded, forceRefresh] = withUnits();

  if (!isLoaded) {
    return null;
  }

  return (
    <ThemeProvider theme={DEFAULT_THEME}>
      <Router>
        <Switch>
          <Route path="/unit/:id">
            <ShowUnit units={units} refreshUnits={forceRefresh} />
          </Route>
          <Route path="/">
            <Home units={units} />
          </Route>
        </Switch>
      </Router>
    </ThemeProvider>
  );
}

export default App;
