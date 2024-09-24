import  pandas as pd
import numpy as np
import tensorflow as tf
from collections import Counter


data=pd.read_csv('train (1).csv',encoding='utf-8')

data=data.loc[:,('text','sentiment')]

data.fillna('yes',inplace=True)

data['sentiment'].replace({
    'neutral':0,
    'negative':1,
    'positive':2
},
 inplace=True)

data['sentiment']=data['sentiment'].astype(np.int64)
data.to_csv('sentiment_review.csv',index=False)


class Preprocess:
    def __init__(self):
        self.PATH='sentiment_review.csv' 
        self.dataset=None
    
    def CreateDataset(self):
        with tf.io.TFRecordWriter('sentiment_analysis.tfrecords') as f:
            f.write(self.PATH)
        self.dataset=tf.data.TFRecordDataset(['sentiment_analysis.tfrecords']).interleave(
            lambda filepaths:tf.data.TextLineDataset(filepaths).skip(1)
        )
        return self.dataset

preprocess=Preprocess()
dataset=preprocess.CreateDataset()


@tf.function
def preprocess(lines):
    defs=[""]*1 + [tf.constant([],dtype=tf.int64)] 
    fields=tf.io.decode_csv(lines,record_defaults=defs)
    X=fields[0]
    y=fields[-1]
    return X,y

dataset=dataset.map(preprocess)


@tf.function
def preprocess_strings(X_batch, y_batch):
    X_batch = tf.strings.substr(X_batch, 0, 100)
    X_batch = tf.strings.regex_replace(X_batch, b"<br\\s*/?>", b" ")
    X_batch = tf.strings.regex_replace(X_batch, b"[^a-zA-Z]", b" ")
    X_batch = tf.strings.split(X_batch)
    return X_batch.to_tensor(default_value=b'<pad>'),y_batch

vocabulary=Counter()

vocabulary=Counter()
for X_batch , y_batch in dataset.batch(32).map(preprocess_strings):
  for reviews in X_batch:
    vocabulary.update((reviews.numpy().flatten()))

class Vocabulary:
    def __init__(self):
        self.out_of_vocabulary=1000
        self.table=None
        self.tableHash=None
        self.truncated_vocab=[
            words for words,counter in vocabulary.most_common()[:1000]
        ]
        self.idx_vocab=tf.range(len(self.truncated_vocab),dtype=tf.int64)
    
    def createTableLookup(self):
        self.tableHash=tf.lookup.KeyValueTensorInitializer(self.truncated_vocab,self.idx_vocab)
        self.table=tf.lookup.StaticVocabularyTable(self.tableHash,self.out_of_vocabulary)
        
        return self.table



        
        
    
