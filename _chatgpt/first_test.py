from openai import OpenAI

client = OpenAI(
  api_key="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"
)

completion = client.chat.completions.create(
  model="gpt-4o-mini",
  store=True,
  messages=[
    {"role": "user", "content": "write a haiku about ai"}
  ]
)

print(completion.choices[0].message);
