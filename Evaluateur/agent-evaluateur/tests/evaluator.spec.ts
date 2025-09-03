import test from 'japa'
import ScoringEngine from 'App/Services/ScoringEngine'

test.group('ScoringEngine', () => {
  test('should evaluate grid and return expected structure', async (assert) => {
    const grid = { /* mock grid */ }
    const result = ScoringEngine.evaluate(grid)
    assert.hasAllKeys(result, ['score10', 'riskClass', 'strengths', 'weaknesses', 'details', 'interpretation'])
  })
})
