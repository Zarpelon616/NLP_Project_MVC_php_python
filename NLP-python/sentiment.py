
import tensorflow as tf
import pandas as pd
import numpy as np 
import seaborn as sns  
import matplotlib.pyplot as plt
from tensorflow import keras


class LoadModel:
    def __init__(self):
        self.PATH=None
        self.model=None
        self.link='sentiment_analysis2.h5'
    
    def getModel(self):
        try:
            self.link=str(self.link)
            if self.PATH is None:
                self.PATH=self.link
                self.model=keras.models.load_model(self.PATH)
                return self.model
        except (ValueError,TypeError) as e:
            raise type(e)(f'Error')

