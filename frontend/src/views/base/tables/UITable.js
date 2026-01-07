import React from 'react'
import {
  CTable,
  CTableHead,
  CTableRow,
  CTableHeaderCell,
  CTableBody,
  CTableDataCell,
} from '@coreui/react'

// Helper para renderizar valores vindos do backend
const renderValue = (value) => {
  if (Array.isArray(value)) {
    return value.join(', ')
  }
  return value ?? '?'
}

const UITable = ({ header, clubs, onFetchField }) => {
  return (
    <CTable hover responsive bordered striped className="table-sm text-center">
      <CTableHead color="dark">
        <CTableRow>
          {header.map(col => (
            <CTableHeaderCell key={col.key}>
              {col.label}
            </CTableHeaderCell>
          ))}
        </CTableRow>
      </CTableHead>

      <CTableBody>
        {clubs.map((club, rowIndex) => (
          <CTableRow key={`row-${club.name ?? rowIndex}`}>
            {header.map(col => (
              <CTableDataCell key={`cell-${club.name}-${col.key}`}>
                {col.key === 'name' ? (
                  <strong>{club.name}</strong>
                ) : (
                  <button
                    className="btn btn-link p-0"
                    onClick={() => onFetchField(club.name, col.key)}
                  >
                    {renderValue(club[col.key])}
                  </button>
                )}
              </CTableDataCell>
            ))}
          </CTableRow>
        ))}
      </CTableBody>
    </CTable>
  )
}

export default UITable
