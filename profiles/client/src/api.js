export function getHost() {
  let apiHost = "";
  if (process.env.NODE_ENV === "development") {
    apiHost = "http://localhost:3001";
  }
  return apiHost;
}

