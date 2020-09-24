const { MongoClient, ObjectId } = require('mongodb')

const connectionUrl = 'mongodb://localhost:27017'
const dbName = 'store'

let db

const init = () =>
  MongoClient.connect(connectionUrl, { useNewUrlParser: true }).then((client) => {
    db = client.db(dbName)
  })

const insertUnit = (unit) => {
  const collection = db.collection('units')
  return collection.insertOne(unit)
}

const getUnits = () => {
  const collection = db.collection('units')
  return collection.find({}).toArray()
}

const updateUnit = (id, unit) => {
  const collection = db.collection('units')
  return collection.updateOne({ _id: ObjectId(id) }, { $set: unit } )
}

module.exports = { init, insertUnit, getUnits, updateUnit }
