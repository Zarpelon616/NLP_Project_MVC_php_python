from sentiment import LoadModel
import requests
import pandas as pd
import tensorflow as tf
from embeddings import Vocabulary
import seaborn as sns
import matplotlib.pyplot as plt


class ExtractData:
    def __init__(self):
        self.requests = None
        self.LINK = 'http://localhost:8000/mvc/api/v1/testimonies'
        self.loadmodel = LoadModel()
        self.model = self.loadmodel.getModel()
        self.data = []
    
    def getData(self):
        if self.requests is None:
            self.requests = requests.get(self.LINK)
            if self.requests.status_code == 200:
                response_data = self.requests.json()
                for depoimento in response_data['depoimentos']:
                    self.data.append({
                        'nome': depoimento['nome'],
                        'mensagem': depoimento['mensagem']
                    })
                self.data = pd.DataFrame(self.data)
            else:
                print(f"Erro ao buscar dados: {self.requests.status_code}")


# Criando uma instância da classe Vocabulary e carregando a tabela de lookup
vocab = Vocabulary()
table = vocab.createTableLookup()

# Exemplo de lookup para testar a tabela de vocabulário
print(table.lookup(tf.constant(['this movie is good'.split()])))

# Extraindo os dados do servidor
extractor = ExtractData()
extractor.getData()
model = extractor.model
data_model = extractor.data


sentiment_counts = {'negative_sentiment': 0, 'positive_sentiment': 0}

for idx in data_model['mensagem']:
    mds_idx = table.lookup(tf.constant([idx.split()]))
    y_pred = model.predict(mds_idx)
    argmax = y_pred[0].argmax()

    if argmax == 0:
        sentiment_counts['negative_sentiment'] += 1
    elif argmax == 2:
        sentiment_counts['positive_sentiment'] += 1


sentiment_df = pd.DataFrame(
    list(sentiment_counts.items()), 
    columns=['Sentiment', 'Count']
)


plt.figure(figsize=(8, 6))
sns.barplot(x='Sentiment', y='Count', data=sentiment_df, palette={'negative_sentiment': 'red', 'positive_sentiment': 'green'})
plt.title('Sentiment Counts')
plt.show()
